<?php

/*Config file for an extension*/

function get_generic_rss_config() {
	$config = array(
		'name' 				=> 'generic_rss',
		'display_name'		=> 'Generic RSS',
		'type'				=> 'generic_rss',
		'pages'				=> array( 'settings',  ),
		'defaultpage'		=> 'settings',
		'content_type' 			=> 'RSS link',
	);
	return $config;
}
?>