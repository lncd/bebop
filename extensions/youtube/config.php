<?php

/*Config file for an extension*/

function get_youtube_config() {
	$config = array(
		'displayname'		=> 'youtube',
		'name' 				=> 'youtube',
		'type'				=> 'youtube',
		'pages'				=> array( 'settings', 'users' ),
		'defaultpage'		=> 'settings',
		'data_feed' 		=> 'http://gdata.youtube.com/feeds/api/users/bebop_replace_username/uploads',
		'sanitise_url'		=> array ( '&feature', '&amp;feature' ),		//remove unwanted/unneeded paremeters from a feed.
		'content_type'	 	=> 'youtube video',
		'content_oembed'	=> true,
		'action_link' 		=> 'http://www.youtube.com/watch/?v="',
	);
	return $config;
}
?>