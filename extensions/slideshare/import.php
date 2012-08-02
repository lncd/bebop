<?php
/**
 * Extension Import function. You will need to modify this function slightly ensure all values are added to the database.
 * Please see the section below on how to do this.
 */

//replace 'twitter' with the 'name' of your extension, as defined in your config.php file.
function bebop_slideshare_import( $extension ) {
	global $wpdb, $bp;
	if ( empty( $extension ) ) {
		bebop_tables::log_general( 'Importer', 'The $extension parameter is empty.' );
		return false;
	}
	else {
		$this_extension = bebop_extensions::get_extension_config_by_name( $extension );
	}
	
	//item counter for in the logs
	$itemCounter = 0;
	$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_' . $this_extension['name'] . '_username' );
	if ( $user_metas ) {
		foreach ( $user_metas as $user_meta ) {
			//Ensure the user is currently wanting to import items.
			if ( bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_active_for_user' ) == 1 ) {
				//check for daylimit
				if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
						
					//we are not using oauth for slideshare - so just send the request
					
					//paramaters required for the request - these are custom for slideshare
					$parameters = array( 
								'api_key' 		=> 'x1j88vyh',
								'ts' 			=> time(),
								'hash'			=> sha1( 'R34Xxw2L'. time()),
								'username_for'	=> bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' ),
					);
					
					$query = $this_extension['data_feed'] . '?' . http_build_query( $parameters );
					bebop_tables::log_error( 'Importer', $query );
					$items = file_get_contents( $query );
					bebop_tables::log_error( 'Importer', $items );
					$items = simplexml_load_string( $items );
					//bebop_tables::log_error( 'Importer', serialize( $items ) );
					
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
					 *.		$item_id				- Must be the ID of the item returned through the data API.
					 * 		$item_content			- The actual content of the imported item.
					 * 		$item_published			- The time the item was published.
					 * 		$action_link			- This is where the link will point to - i.e. where the user can click to get more info.
					 */
					
					
					//Edit the following two variables to point to where the relevant content is being stored in the API:
					$errors		 = $items->error;
					
					if ( $items && ! $errors ) {
						foreach ( $items as $item ) {
							if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
								
								
								//Edit the following three variables to point to where the relevant content is being stored:
								$item_id			= $item->id;
								$item_content		= $item->text;
								$item_published		= $item->created_at;
								$action_link 		= str_replace( 'bebop_replace_username', $username , $extension['action_link'] ) . $item_id;
								//Stop editing - you should be all done.
								
								
								if ( bebop_create_buffer_item(
												array(
													'user_id'			=> $user_meta->user_id,
													'extention'			=> $this_extension['name'],
													'type'				=> $this_extension['content_type'],
													'content'			=> $item_content,
													'content_oembed'	=> $this_extension['content_oembed'],
													'item_id'			=> $item_id,
													'raw_date'			=> gmdate( 'Y-m-d H:i:s', strtotime( $item_published ) ),
													'actionlink'		=> $action_link,
												)
								) ) {
									$itemCounter++;
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
	}
	//return the result
	return $itemCounter . ' ' . $this_extension['content_type'] . 's';
}