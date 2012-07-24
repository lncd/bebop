<?php
/**
 * Import starter
 */

function bebop_twitter_start_import() {
	if ( ! bebop_tables::check_option_exists( 'bebop_twitter_consumer_key' ) ){
		//add record to the log
		bebop_tables::log_error( 1, 'Twitter', 'No oAuth token' );
		return false;
	}
	$importer = new bebop_twitter_import();
	return $importer->doImport();
}

/**
 * Twitter Import Class
 */

class bebop_twitter_import {
	//do the import
	public function doImport() {
		global $bp, $wpdb;
		//item counter for in the logs
		$itemCounter = 0;
		
		if ( bebop_tables::check_option_exists( 'bebop_twitter_consumer_key' ) ) {

			$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_twitter_oauth_token' );

			if ( $user_metas ) {
				foreach ( $user_metas as $user_meta ) {
					//check for daylimit
					$limitReached = bebop_filters::import_limit_reached( 'twitter', $user_meta->user_id );
					
					if ( ! $limitReached && bebop_tables::get_user_meta_value( $user_meta->user_id, 'bebop_twitter_sync_to_activity_stream' ) ) {
						//Handle the OAuth requests
						$OAuth = new bebop_oauth();
						$OAuth->setCallbackUrl('$bp->root_domain');
						$OAuth->setConsumerKey( bebop_tables::get_option_value( 'bebop_twitter_consumer_key' ) );
						$OAuth->setConsumerSecret( bebop_tables::get_option_value( 'bebop_twitter_consumer_secret' ) );
						$OAuth->setAccessToken( bebop_tables::get_user_meta_value( $user_meta->user_id,'bebop_twitter_oauth_token' ) );
						$OAuth->setAccessTokenSecret( bebop_tables::get_user_meta_value( $user_meta->user_id,'bebop_twitter_oauth_token_secret' ) );
						
						$items = $OAuth->oAuthRequest( 'http://api.twitter.com/1/statuses/user_timeline.xml' );
						$items = simplexml_load_string( $items );
						
						if ($items && !$items->error) {
							//update the user username
							$username = ''.$items->status->user->screen_name[0];
							bebop_tables::update_user_meta( $user_meta->user_id, 'twitter', 'bebop_twitter_username', $username );
							//go through tweets
							foreach ( $items as $tweet ) {
								$activity_info = bp_activity_get( array( 'filter' => array( 'secondary_id' => $user_meta->user_id."_".$tweet->id ),'show_hidden' => true ) );
								
								if ( ( empty( $activity_info['activities'][0] ) ) && ( ! bp_activity_check_exists_by_content( $tweet->text ) )  && ( ! $limitReached ) ) {
									if(bebop_create_buffer_item( array(
										'user_id'			=> $user_meta->user_id,
										'extention'			=> 'twitter',
										'type'				=> 'tweet',
										'content'			=> $tweet->text,
										'content_oembed'	=> false,			//true if you want to use oembed, false if not.
										'item_id'			=> $tweet->id,
										'raw_date'			=> gmdate( 'Y-m-d H:i:s', strtotime( $tweet->created_at ) ),
										'actionlink'		=> 'http://www.twitter.com/' . $username . '/status/'.$tweet->id
										)
									)) {
										$itemCounter++;
									}
								}
							}
						}
					}
				}
			}
		}
		//add record to the log
		bebop_tables::log_general( 'bebop_twitter_import', 'imported ' . $itemCounter . ' tweets.' );
		//return number of items imported
		return $itemCounter;
	}
}
 