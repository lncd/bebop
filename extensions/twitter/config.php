<?php

/*Config file for twitter*/

function get_twitter_config() {
	$config = array(
		'displayname'	=> 'twitter',
		'name' 			=> 'twitter',
		'pages'			=> array( 'settings', 'filters', 'users' ),
		'defaultpage'	=> 'settings',
		'hashtag'		=> '#twitter',
	);
	return $config;
}
?>