<?php

/*Config file for youtube*/

function get_youtube_config() {
	$config = array(
		'displayname'	=> 'youtube',
		'name' 			=> 'youtube',
		'type'			=> 'youtube',
		'pages'			=> array( 'settings', 'users' ),
		'defaultpage'	=> 'settings'
	);
	return $config;
}

?>