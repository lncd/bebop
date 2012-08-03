<?php

/*Config file for an extension*/

function get_slideshare_config() {
	$config = array(
		'displayname'			=> 'slideshare',
		'name' 					=> 'slideshare',
		'pages'					=> array( 'settings', 'filters', 'users' ),
		'defaultpage'			=> 'settings',
		'data_feed' 			=> 'http://www.slideshare.net/api/2/get_slideshows_by_user',
		'content_type' 			=> 'slideshow',
		'content_oembed' 		=> true,
	);
	return $config;
}
?>
