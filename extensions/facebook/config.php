<?php

/*Config file for an extension*/

function get_facebook_config() {
	$config = array(
		'name' 						=> 'facebook',
		'display_name'				=> 'Facebook',
		'pages'						=> array( 'settings', ),
		'defaultpage'				=> 'settings',
		'request_token_url' 		=> 'https://www.facebook.com/dialog/oauth?client_id=APP_ID&redirect_uri=REDIRECT_URI&state=STATE',
		'access_token_url' 			=> 'https://graph.facebook.com/oauth/access_token?client_id=APP_ID&redirect_uri=REDIRECT_URI&client_secret=APP_SECRET&code=CODE',
		'extend_access_token_url'	=> 'https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=APP_ID&client_secret=APP_SECRET&fb_exchange_token=SHORT_TOKEN',
		'people_data'				=> 'https://graph.facebook.com/USER_ID',
		'data_feed' 				=> 'https://graph.facebook.com/me/feed?access_token=',
		'content_type' 				=> __( 'Status Update', 'bebop' ),
		'content_oembed' 			=> false,
		'action_link' 				=> 'http://www.twitter.com/bebop_replace_username/status/',
	);
	return $config;
}
?>