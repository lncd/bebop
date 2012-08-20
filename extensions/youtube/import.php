<?php
/**
 * Extension Import function. You will need to modify this function slightly ensure all values are added to the database.
 * Please see the section below on how to do this.
 */

//replace 'youtube' with the 'name' of your extension, as defined in your config.php file.
function bebop_youtube_import( $extension ) {
	
	global $wpdb, $bp;
	if ( empty( $extension ) ) {
		bebop_tables::log_general( 'Importer', 'The $extension parameter is empty.' );
		return false;
	}
	else {
		$this_extension = bebop_extensions::get_extension_config_by_name( $extension );
	}
	require_once (ABSPATH . WPINC . '/class-feed.php');
	$itemCounter = 0;
	$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_' . $this_extension['name'] . '_username' );
	
	if ( $user_metas ) {
		foreach ( $user_metas as $user_meta ) {
			$errors = null;
			$items 	= null;
			
			//Ensure the user is wanting to import items.
			if ( bebop_tables::check_user_meta_exists( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' )  && bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_active_for_user' ) ) {
				//Check the user has not gone past their import limot for the day.
				if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
					
					/* 
					 * ******************************************************************************************************************
					 * Depending on the data source, you will need to switch how the data is retrieved. If the feed is RSS, use the 	*
					 * SimplePie method, as shown in the youtube extension. If the feed is oAuth API based, use the oAuth implementation*
					 * as shown in thr twitter extension. If the feed is an API without oAuth authentication, use SlideShare			*
					 * ******************************************************************************************************************
					 */
					
					//Youtube is RSS based - so use SimplePie to gather the data
					
					//Replace 'bebop_replace_username' with the username of the user.
					$importUrls = str_replace( 'bebop_replace_username', bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' ), $this_extension['data_feed']);
					
					//Configure the feed
					$feed = new SimplePie();
					$feed->set_feed_url( $importUrls );
					$feed->set_cache_class( 'WP_Feed_Cache' );
					$feed->set_file_class( 'WP_SimplePie_File' );
					$feed->enable_cache( false );
					$feed->set_cache_duration( 0 );
					do_action_ref_array( 'wp_feed_options', array( $feed, $importUrls ) );
					$feed->init();
					$feed->handle_content_type();
					
					/* 
					 * ******************************************************************************************************************
					 * We can get as far as loading the items, but you will need to adjust the values of the variables below to match 	*
					 * the values from the extension's feed.																			*
					 * This is because each feed return data under different parameter names, and the simplest way to get around this is*
					 * to quickly match the values. To find out what values you should be using, consult the provider's documentation.	*
					 * You can also contact us if you get stuck - details are in the 'support' section of the admin homepage.			*
					 * ******************************************************************************************************************
					 * 
					 * Values you will need to check and update are:
					 * 		$errors 				- Must point to the error boolean value (true/false)
					 *.		$link and /or $item_id	- Must be the ID of the item returned through the data feed.
					 * 		$description			- The actual content of the imported item.
					 * 		$item_published			- The time the item was published.
					 * 		$action_link			- This is where the link will point to - i.e. where the user can click to get more info.
					 */
					 
					 
					//Edit the following variables to point to where the relevant content is being stored in the feed:
					$errors	 = $feed->error;
					
					if ( ! $errors ) {
						
						//Edit the following variable to point to where the relevant content is being stored in the feed:
						$items = $feed->get_items();
						
						if ( $items ) {
							foreach ( $items as $item ) {
								if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
										
									//Edit the following variables to point to where the relevant content is being stored:
									$link = $item->get_permalink();
									$id_array = explode( '=', $link );
									$id = $id_array[1];
									
									$description = $item->get_content();
									$item_published = date( 'Y-m-d H:i:s', strtotime( $item->get_date() ) );
									$action_link = $this_extension['action_link'] . $id;
									//cleanup the link if needed
									foreach ( $this_extension['sanitise_url'] as $clean ) {
										$id = str_replace( $clean, '', $id );
									}
									//Stop editing - you should be all done.
									
									
									//generate an $item_id
									$item_id = bebop_generate_secondary_id( $user_meta->user_id, $id, $item_published );
									
									//check if the secondary_id already exists
									$secondary = bebop_tables::fetch_individual_oer_data( $item_id );
									//if the id is found, we have the item in the database and all following items (feeds return most recent items first). Move onto the next user..
									if ( ! empty( $secondary->secondary_item_id ) ) {
										break;
									}
									
									//Only for content which has a description.
									if ( ! empty( $description ) ) {
										//crop the content if it is too long
										if ( strlen( $description ) > 500 ) {
											$description = substr( $description, 0, 500 ) . " <a href='" . $this_extension['action_link'] . $item_id . "'>read more</a>";
										}
										
										//This manually puts the link and description together with a line break, which is needed for oembed.
										$item_content = $action_link . '
										' . $description;
									}
									else {
										$item_content = $action_link;
									}
									
									$returnCreate = bebop_create_buffer_item(
													array(
														'user_id' 			=> $user_meta->user_id,
														'extention' 		=> $this_extension['display_name'],
														'type' 				=> $this_extension['content_type'],
														'content' 			=> $item_content,
														'content_oembed' 	=> $this_extension['content_oembed'],
														'item_id' 			=> $item_id,
														'raw_date' 			=> $item_published,
														'actionlink'	 	=> $action_link,
													)
									);
									
									if ( $returnCreate ) {
										$itemCounter++;
									}
								}
							}
						}
					}
					else {
						bebop_tables::log_error( 'Importer - ' . $this_extension['display_name'] , 'feed error: ' . $errors );
					}
				}
			}
		}
	}
	//return the result
	return $itemCounter . ' ' . $this_extension['content_type'] . 's';
}
