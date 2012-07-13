<?php

bebop_extensions::load_extensions();

function bebop_create_buffer_item($params) {
	 global $bp, $wpdb;

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
        $secondary = $wpdb->get_row( $wpdb->prepare("SELECT secondary_item_id FROM " . $wpdb->base_prefix . "bp_bebop_oer_buffer WHERE secondary_item_id='" . $params['item_id'] . "'") );

        //do we already have this content if so do not import this item
        if($secondary == null){
			$content = '';
			if( $params['content_oembed'] === true ) {
	            //set the content 
	            $content = $originalText;
			}
			else {
				$content = '<div class="bebop_activity_container ' . $params['extention'] . '">' . $originalText . '</div>';				
			}

            if( ! bebop_check_existing_content_buffer($originalText)) {

                $oer_user_id 		   = $params['user_id'];
                $oer_type              = $params['extention'];
                $oer_content           = $content;
                $oer_secondary_item_id = $params['user_id'] . "_" . $params['item_id'];
                $oer_date_recorded     = $params['raw_date'];

                if (bebop_tables::get_option_value('bebop_'. $params['extention'] . '_hide_sitewide') == "on") {
                    $oer_hide_sitewide = 1;
                }
				else {
					$oer_hide_sitewide = 0;
				}

                $activity->action .= '<a href="' . bp_core_get_user_domain($params['user_id']) .'" title="' . bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
                $activity->action .= ' ' . __('posted&nbsp;a', 'bebop' . $extention['name'])." ";
                $activity->action .= '<a href="' . $params['actionlink'] . '" target="_blank" rel="external"> '.__($params['type'], 'bebop_'.$extention['name']);
                $activity->action .= '</a>: ';

                //extra check to be sure we don't have a empty activity
                $cleanContent = '';
                $cleanContent = trim(strip_tags($activity->content));
				
                //check if item does not exist in the blacklist
                if( (bebop_tables::get_user_meta_value($params['user_id'], 'bebop_blacklist_ids') ) && ( ! empty($cleanContent)) ) {
                    if ( ! preg_match("/" . $params['item_id'] . "/i", bebop_tables::get_user_meta_value($params['user_id'], 'bebop_blacklist_ids', 1))) {                    	
                        $activity->save();
                        bebop_filters::day_increase($params['extention'], $params['user_id']);
                    }

                }
                else{
                    $activity->save();
                    bebop_filters::day_increase($params['extention'], $params['user_id']);
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    return true;
}

function bebop_create_activity($params) {

    global $bp, $wpdb;

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
        $secondary = $wpdb->get_row( $wpdb->prepare("SELECT secondary_item_id FROM {$bp->activity->table_name} WHERE secondary_item_id='" . $params['item_id'] . "'") );

        //do we already have this content if so do not import this item
        if($secondary == null){
			$content = '';
			if( $params['content_oembed'] === true ) {
	            //set the content 
	            $content = $originalText;
			}
			else {
				$content = '<div class="bebop_activity_container ' . $params['extention'] . '">' . $originalText . '</div>';				
			}

            $activity = new BP_Activity_Activity();
            if( ! bebop_check_existing_content_activity($originalText)){

                add_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);
				

                $activity->user_id           = $params['user_id'];
                $activity->component         = 'bebop_oer_plugin';
                $activity->type              = $params['extention'];
                $activity->content           = $content;
                $activity->secondary_item_id = $params['user_id'] . "_" . $params['item_id'];
                $activity->date_recorded     = $params['raw_date'];

                if (bebop_tables::get_option_value('bebop_'. $params['extention'] . '_hide_sitewide') == "on") {
                    $activity->hide_sitewide = 1;
                }
				else {
					$activity->hide_sitewide = 0;
				}

                $activity->action .= '<a href="' . bp_core_get_user_domain($params['user_id']) .'" title="' . bp_core_get_username($params['user_id']).'">'.bp_core_get_user_displayname($params['user_id']).'</a>';
                $activity->action .= ' ' . __('posted&nbsp;a', 'bebop' . $extention['name'])." ";
                $activity->action .= '<a href="' . $params['actionlink'] . '" target="_blank" rel="external"> '.__($params['type'], 'bebop_'.$extention['name']);
                $activity->action .= '</a>: ';

                remove_filter('bp_activity_action_before_save', 'bp_activity_filter_kses', 1);

                //extra check to be sure we don't have a empty activity
                $cleanContent = '';
                $cleanContent = trim(strip_tags($activity->content));
				
                //check if item does not exist in the blacklist
                if( (bebop_tables::get_user_meta_value($params['user_id'], 'bebop_blacklist_ids') ) && ( ! empty($cleanContent)) ) {
                    if ( ! preg_match("/" . $params['item_id'] . "/i", bebop_tables::get_user_meta_value($params['user_id'], 'bebop_blacklist_ids', 1))) {                    	
                        $activity->save();
                        bebop_filters::day_increase($params['extention'], $params['user_id']);
                    }

                }
                else{
                    $activity->save();
                    bebop_filters::day_increase($params['extention'], $params['user_id']);
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    return true;
}

//This is a hook into the activity filter options.
add_action('bp_activity_filter_options', 'load_new_options');

//This is a hook into the member activity filter options.
add_action('bp_member_activity_filter_options', 'load_new_options');

//This function loads additional fitler options for the extensions.
function load_new_options()
{		
	$handle = opendir( WP_PLUGIN_DIR . "/bebop/extensions" );
    if ( $handle ) {
    	while ( false !== ( $file = readdir($handle) ) ) {
        	if ( $file != "." && $file != ".." && $file != ".DS_Store" ) {
            	if ( file_exists( WP_PLUGIN_DIR."/bebop/extensions/" . $file . "/core.php" ) ) {
                  	echo '<option value="' . ucfirst($file) .'">' . ucfirst($file) . '</option>';
               	}
			}
		}
	}	
}

function bebop_check_existing_content_activity ($content){

    global $wpdb, $bp;

    $content = strip_tags($content);
    $content = trim($content);

    $wpdb->get_var("SELECT content FROM {$bp->activity->table_name} WHERE content LIKE '%" . $content . "%'");
}

function bebop_check_existing_content_buffer($content){

    global $wpdb, $bp;

    $content = strip_tags($content);
    $content = trim($content);

    $wpdb->get_var("SELECT content FROM " . $wpdb->base_prefix . "bp_bebop_oer_buffer WHERE content LIKE '%" . $content . "%'");
}