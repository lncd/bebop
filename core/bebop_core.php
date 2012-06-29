<?php
/* Test classes and functions */

//Tests
/*bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_general('test log', 'test log message');

bebop_tables::add_option('test_option', 'test_option');
bebop_tables::add_option('test_option', 'test_option');
bebop_tables::update_option('test_option', 'updated text');
bebop_tables::remove_option('test_option');
bebop_tables::add_option('test_option', 'test_option');

bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_error(123, 'test error', 'test error message');
*/

bebop_extensions::load_extensions();

function bebop_create_activity($params){

    global $bp, $wpdb;

    /**
     * buddystreamCreateActivity(array(
     *     'user_id'    => $user_meta->user_id,
     *     'extention'  => 'facebook',
     *     'type'       => 'photo',
     *     'content'    => $content,
     *     'item_id'    => $item['id'],
     *     'raw_date'   => $item['created_time'],
     *     'actionlink' => 'url_to_original_item')
     *  ));
     *
     */

    if(is_array($params)) {

        //load config of extention
        $originalText = $params['content'];
		
        foreach(bebop_extensions::get_extension_configs() as $extention){
            if(isset($extention['hashtag'])){
                $originalText = str_replace($extention['hashtag'], "", $originalText);
                $originalText = trim($originalText);
            }
        }

        //check if the secondary_id already exists
        $secondary = $wpdb->get_row(
                     $wpdb->prepare("SELECT secondary_item_id
                                     FROM {$bp->activity->table_name}
                                     WHERE secondary_item_id='" . $params['item_id'] . "'")
                            );

        //do we already have this content if so do not import this item
        if($secondary == null){

            //set the content 
            $content = "";
            $content = '<div class="buddystream_activity_container ' . $params['extention'] . '">' . $originalText . '</div>';

            $activity = new BP_Activity_Activity();
            if( ! bebop_check_existing_content($originalText)){

                add_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

                $activity->user_id           = $params['user_id'];
                $activity->component         = $params['extention'];
                $activity->type              = $params['extention'];
                $activity->content           = $content;
                $activity->secondary_item_id = $params['user_id'] . "_" . $params['item_id'];
                $activity->date_recorded     = $params['raw_date'];

                if (bebop_tables::get_option_value('buddystream_'. $params['extention'] . '_hide_sitewide') == "on") {
                    $activity->hide_sitewide = 1;
                }
				else {
					$activity->hide_sitewide = 0;
				}

                $activity->action .= '<a href="' . bp_core_get_user_domain($params['user_id']) .'" title="' . bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
                $activity->action .= ' ' . __('posted&nbsp;a', 'buddystream_' . $extention['name'])." ";
                $activity->action .= '<a href="' . $params['actionlink'] . '" target="_blank" rel="external"> '.__($params['type'], 'buddystream_'.$extention['name']);
                $activity->action .= '</a>: ';

                remove_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

                //extra check to be sure we don't have a empty activity
                $cleanContent = '';
                $cleanContent = trim(strip_tags($activity->content));

                //check if item does not exist in the blacklist
                if(bebop_tables::get_user_meta_value($params['user_id'], 'buddystream_blacklist_ids') && !empty($cleanContent)){

                    if (!preg_match("/" . $params['item_id'] . "/i", bebop_tables::get_user_meta_value($params['user_id'], 'buddystream_blacklist_ids', 1))) {
                        $activity->save();
                       // BuddyStreamFilters::updateDayLimitByOne($params['extention'], $params['user_id']);
                    }

                }else{
                    $activity->save();
					
                   // BuddyStreamFilters::updateDayLimitByOne($params['extention'], $params['user_id']);
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    return true;
}

function bebop_check_existing_content($content){

    global $wpdb, $bp;

    $content = strip_tags($content);
    $content = trim($content);

    $wpdb->get_var("SELECT content FROM {$bp->activity->table_name} WHERE content LIKE '%" . $content . "%'");
}