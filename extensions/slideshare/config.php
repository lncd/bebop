<?php

/*Config file for an extension*/

function get_slideshare_config() {
	$config = array(
		'displayname'			=> 'slideshare',
		'name' 					=> 'slideshare',
		'pages'					=> array( 'settings', 'filters', 'users' ),
		'defaultpage'			=> 'settings',
		'data_feed' 			=> 'http://www.slideshare.net/api/2/get_slideshows_by_user',
		'content_type' 			=> 'slideshare content',
		'content_oembed' 		=> true,
		'action_link' 			=> 'http://www.twitter.com/bebop_replace_username/status/',
	);
	return $config;
}

//test
$timestamp = time();
//echo 'http://www.slideshare.net/api/2/get_slideshows_by_user?api_key=x1j88vyh&ts=' . $timestamp . '&hash=' . sha1( 'R34Xxw2L'. $timestamp) . '&username_for=jacksonj04';
?>
