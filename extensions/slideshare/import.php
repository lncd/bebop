<?php
/**
 * Extension Import function. You will need to modify this function slightly ensure all values are added to the database.
 * Please see the section below on how to do this.
 */

//replace 'slideshare' with the 'name' of your extension, as defined in your config.php file.
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
			$errors = null;
			$items 	= null;
			
			//Ensure the user is currently wanting to import items.
			if ( bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_active_for_user' ) == 1 ) {
				//check for daylimit
				if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
						
					//we are not using oauth for slideshare - so just build the api request and send it using our bebop-data class.
					//If you are using a service that uses oAuth, then use the oAuth class.
					$data_request = new bebop_data();
					//paramaters required for the request - these are custom for slideshare - edit these to match the paremeters required by the API.
					$data_request->set_parameters( 
								array( 
											'api_key' 		=> bebop_tables::get_option_value( 'bebop_' . $this_extension['name'] . '_consumer_key' ),
											'ts' 			=> time(),
											'hash'			=> sha1( bebop_tables::get_option_value( 'bebop_' . $this_extension ['name']. '_consumer_secret' ) . time() ),
											'username_for'	=> bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_' . $this_extension['name'] . '_username' ),
								)
					);
					$query = $data_request->build_query( $this_extension['data_feed'] );
					$data = $data_request->execute_request( $query );
					$data = simplexml_load_string( $data );
					
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
					 * 		$errors 				- Must point to the error value
					 * 		$items					- Must point to the items that will be imported into the plugin.
					 * 		$item_id				- Must be the ID of the item returned through the data API.
					 * 		$item_content			- The actual content of the imported item.
					 * 		$item_published			- The time the item was published.
					 * 		$action_link			- This is where the link will point to - i.e. where the user can click to get more info.
					 */
					
					//Edit the following variable to point to where the relevant content is being stored in the API:
					$errors = $data->Message;
					
					if ( ! $errors ) {
						//Edit the following variable to point to where the relevant content is being stored in the API:
						$items 	= $data->Slideshow;
						
						foreach ( $items as $item ) {
							if ( ! bebop_filters::import_limit_reached( $this_extension['name'], $user_meta->user_id ) ) {
								
								//Edit the following three variables to point to where the relevant content is being stored:
								$item_id			= $item->ID;
								$action_link		= $item->URL;
								$description		= $item->Description;
								
								$item_published = $item->Created;
								//Stop editing - you should be all done.
								
								if( ! empty( $description) ) {
									//crop the content if it is too long
									if ( strlen( $description ) > 500 ) {
										$description = substr( $description, 0, 500 ) . " <a href='" . $action_link . "'>read more</a>";
									}
									
									//This manually puts the link and description together with a line break, which is needed for oembed.
									$item_content = $action_link . '
									' . $description;
								}
								else {
									$item_content = $action_link;
								}
								
								
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