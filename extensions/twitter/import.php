<?php
/**
 * Import starter
 */

function BuddystreamTwitterImportStart() {
	
	$user_metas = bebop_tables::get_user_ids_from_meta_name('tweetstream_token');

    if( ! bebop_tables::check_option_exists('tweetstream_user_settings_syncbp')){
        //add record to the log
        bebop_tables::log_error(1, 'Twitter', 'Import disabled');
        return false;
    }

    $importer = new BuddyStreamTwitterImport();
    return $importer->doImport();
}

/**
 * Twitter Import Class
 */

class BuddyStreamTwitterImport{

    //do the import
    public function doImport() {

        global $bp, $wpdb;

        //item counter for in the logs
        $itemCounter = 0;

        if (bebop_tables::check_option_exists("tweetstream_consumer_key")) {
            if ( bebop_tables::check_option_exists('tweetstream_user_settings_syncbp') ) {

                $user_metas = bebop_tables::get_user_ids_from_meta_name('tweetstream_token');

                if ($user_metas) {
                    foreach ($user_metas as $user_meta) {

                        //check for daylimit
                        $limitReached = bebop_filters::import_limit_reached('twitter', $user_meta->user_id);

                        if (!$limitReached && bebop_tables::get_user_meta_value($user_meta->user_id, 'tweetstream_synctoac')) {

                            //Handle the OAuth requests
                            $OAuth = new BuddyStreamOAuth();
                            $OAuth->setCallbackUrl($bp->root_domain);
                            $OAuth->setConsumerKey(bebop_tables::get_option("tweetstream_consumer_key"));
                            $OAuth->setConsumerSecret(bebop_tables::get_option("tweetstream_consumer_secret"));
                            $OAuth->setAccessToken(bebop_tables::get_user_meta_value($user_meta->user_id,'tweetstream_token'));
                            $OAuth->setAccessTokenSecret(bebop_tables::get_user_meta_value($user_meta->user_id,'tweetstream_tokensecret'));

                            $items = $OAuth->oAuthRequest('http://api.twitter.com/1/statuses/user_timeline.xml');
                            $items = simplexml_load_string($items);

                            if ($items && !$items->error) {

                                //update the user screen_name
                                $screenName = ''.$items->status->user->screen_name[0];
                                bebop_tables::update_user_meta($user_meta->user_id,'tweetstream_screenname', $screenName);

                                //go through tweets
                                foreach ($items as $tweet) {

                                    $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $user_meta->user_id."_".$tweet->id),'show_hidden' => true));
                                    if ($activity_info['activities'][0] == null && !bp_activity_check_exists_by_content($tweet->text) && !$limitReached) {

                                        $returnCreate = buddystreamCreateActivity(array(
                                                'user_id'       => $user_meta->user_id,
                                                'extention'     => 'twitter',
                                                'type'          => 'tweet',
                                                'content'       => $tweet->text,
                                                'item_id'       => $tweet->id,
                                                'raw_date'      => gmdate('Y-m-d H:i:s', strtotime($tweet->created_at)),
                                                'actionlink'    => 'http://www.twitter.com/' . $screenName . '/status/'.$tweet->id
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
            }
        }

        //add record to the log
        bebop_tables::log_general("Twitter", " imported ".$itemCounter." tweets.");

        //return number of items imported
        return $itemCounter;

    }
}