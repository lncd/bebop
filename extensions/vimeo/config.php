<?php

/*Config file for an extension*/

function get_vimeo_config() {
	$config = array(
		'name' 					=> 'vimeo',
		'pages'					=> array( 'settings', ),
		'defaultpage'			=> 'settings',
		'data_feed' 			=> 'http://vimeo.com/api/v2/',
		'content_type' 			=> 'Vimeo Video',
		'content_oembed' 		=> true,
	);
	return $config;
}
?>
