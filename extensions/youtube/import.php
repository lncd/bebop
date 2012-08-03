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
			//Ensure the user is wanting to import items.
			if ( bebop_tables::check_user_meta_exists( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' )  && bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_active_for_user' ) ) {
				//get these urls for import
				$importUrls = str_replace( 'bebop_replace_username', bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' ), $this_extension['data_feed']);
				$items = null;
				$feed = new SimplePie();
				$feed->set_feed_url( $importUrls );
				$feed->set_cache_class( 'WP_Feed_Cache' );
				$feed->set_file_class( 'WP_SimplePie_File' );
				$feed->enable_cache( false );
				$feed->set_cache_duration( 0 );
				do_action_ref_array( 'wp_feed_options', array( $feed, $importUrl ) );
				$feed->init();
				$feed->handle_content_type();
				
				/* 
				 * ******************************************************************************************************************
				 * We can get as far as loading the items, but you will need to adjust the values of the variables below to match 	*
				 * the values from the extension's API.																				*
				 * This is because each API return data under different parameter names, and the simplest way to get around this is *
				 * to quickly match the values. To find out what values you should be using, consult the provider's documentation.	*
				 * You can also contact us if you get stuck - details are in the 'support' section of the admin homepage.			*
				 * ******************************************************************************************************************
				 * 
				 * Values you will need to check and update are:
				 * 		$errors 				- Must point to the error boolean value (true/false)
				 *.		$link and /or $item_id	- Must be the ID of the item returned through the data API.
				 * 		$description			- The actual content of the imported item.
				 * 		$item_published			- The time the item was published.
				 * 		$action_link			- This is where the link will point to - i.e. where the user can click to get more info.
				 */
				 
				 
				//Edit the following variablesto point to where the relevant content is being stored in the API:
				$errors	 = $feed->error;
				
				
				if ( ! $errors ) {
					$items = $feed->get_items();
					if ( $items ) {
						foreach ( $items as $item ) {
							if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
								//steps to get the id of the item - might need changing
								$link = $item->get_permalink();
								$id_array = explode( '=', $link );
								$item_id = $id_array[1];
								
								
								//cleanup the link
								foreach ( $this_extension['sanitise_url'] as $clean ) {
									$item_id = str_replace( $clean, '', $item_id );
								}
								
								
								//might need changing
								$description = $item->get_content();
								
								//crop the content if it is too long
								if ( strlen( $description ) > 500 ) {
									$description = substr( $description, 0, 500 ) . " <a href='" . $this_extension['action_link'] . $item_id . "'>read more</a>";
								}
								
								//This manually puts the link and description together with a line break, which is needed for oembed.
								$item_content = $this_extension['action_link'] . $item_id . '
								' . $description;
								
								//might need changing
								$item_published = strtotime( $item->get_date() );
								
								//might need changing
								$action_link = $this_extension['action_link'] . $item_id;
								
								
								//should not have to edit anything else.
								$returnCreate = bebop_create_buffer_item(
												array(
													'user_id' 			=> $user_meta->user_id,
													'extention' 		=> $this_extension['name'],
													'type' 				=> $this_extension['content_type'],
													'content' 			=> $item_content,
													'content_oembed' 	=> $this_extension['content_oembed'],
													'item_id' 			=> $item_id,
													'raw_date' 			=> date( 'Y-m-d H:i:s', $item_published ),
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
					bebop_tables::log_error( 'Importer - ' . ucfirst( $this_extension['name'] ), 'feed error: ' . $errors );
				}
			}
		}
	}
	//return the result
	return $itemCounter . ' ' . $this_extension['content_type'] . 's';
}
