<?php

/*Config file for an extension*/

function get_generic_rss_config() {
	$config = array(
		'name' 				=> 'generic_rss',
		'type'				=> 'generic_rss',
		'pages'				=> array( 'settings',  ),
		'defaultpage'		=> 'settings',
	);
	return $config;
}
?>