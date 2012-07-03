<?php
/**
 * Import starter
 */

function bebop_youtube_start_import() {
    $importer = new bebop_youtube_import();
    return $importer->doImport();
}

/**
 * Youtube Import Class
 */

class bebop_youtube_import {

    public function doImport() {
        global $bp, $wpdb;

        require_once (ABSPATH . WPINC . '/class-feed.php');

        $itemCounter = 0;

        $user_metas = $wpdb->get_results( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta where meta_key='bs_youtube_username' order by meta_value"));

        if ($user_metas) {
            foreach ($user_metas as $user_meta) {
                //check for daylimit
             //   $limitReached = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);

 				  if (bebop_tables::check_user_meta_exists($user_meta->user_id, 'bebop_youtube_username')) {
                    //get these urls for import
                    $importUrls = 'http://gdata.youtube.com/feeds/api/users/' . bebop_tables::get_user_meta_value($user_meta->user_id, 'bebop_youtube_username') . '/uploads';

                        $items = null;
                        $feed = new SimplePie();
                        $feed->set_feed_url($importUrls);
                        $feed->set_cache_class('WP_Feed_Cache');
                        $feed->set_file_class('WP_SimplePie_File');
						$feed->enable_cache(false);
                        $feed->set_cache_duration(0);
                        do_action_ref_array('wp_feed_options', array(&$feed, $importUrl));
                        $feed->init();
                        $feed->handle_content_type();
						
                        if (!$feed->error) {
                            $items = $feed->get_items();
                        }
						else {
							bebop_tables::log_general("bebop_youtube_import", 'feed error: ' . $feed->error);
						}
						
                        if ($items) {
                            foreach ($items as $item) {
                               // $limitReached = BuddyStreamFilters::limitReached('youtube', $user_meta->user_id);

                                // get video player URL
                                $link = $item->get_permalink();

                                //get the video id from player url
                                $videoIdArray = explode("=", $link);
                                $videoId = $videoIdArray[1];
                                $videoId = str_replace("&feature", "", $videoId);
                                $videoId = str_replace("&amp;feature", "", $videoId);

                                //get the thumbnail
                                $thumbnail = "http://i.ytimg.com/vi/" . $videoId . "/0.jpg";

                                $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $user_meta->user_id . "_" . $videoId), 'show_hidden' => true));
                                if (!$activity_info['activities'][0]->id && !$limitReached) {

                                    $description = "";
                                    $description = $item->get_content();
                                    if (strlen($description) > 400) {
                                        $description = substr($description, 0, 400) . "... <a href='http://www.youtube.com/watch/?v=" . $videoId . "'>read more</a>";
                                    }

                                    $content = '<a href="http://www.youtube.com/watch/?v=' . $videoId . '" class="bs_lightbox" id="' . $videoId . '" title="' . $item->get_title() . '"><img src="' . $thumbnail . '"></a><b>' . $item->get_title() . '</b> ' . $description;

                                    //pre convert date
                                    $ts = strtotime($item->get_date());

                                    $returnCreate = buddystreamCreateActivity(array(
                                            'user_id' => $user_meta->user_id,
                                            'extention' => 'youtube',
                                            'type' => 'Youtube video',
                                            'content' => $content,
                                            'item_id' => $videoId,
                                            'raw_date' => date("Y-m-d H:i:s", $ts),
                                            'actionlink' => 'http://www.youtube.com/' . get_user_meta($user_meta->user_id, 'bebop_youtube_username', 1)
                                        )
                                    );

                                    if($returnCreate){
                                        $itemCounter++;
                                    }
                                }
                            }
                        
                    }
                }
            }
        }

        //add record to the log
		bebop_tables::log_general("bebop_youtube_import", "imported ".$itemCounter." video's.");
        //return number of items imported
        return $itemCounter;
    }
}