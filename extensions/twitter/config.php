<?php

/*Config file for twitter*/

function get_twitter_config() {
	$config = array(
		'displayname'	=> 'twitter',
		'name' 			=> 'twitter',
		'pages'			=> array( 'settings', 'filters', 'users' ),
		'defaultpage'	=> 'settings',
		'hashtag'		=> '#twitter',
		'request_token_url' => 'http://api.twitter.com/oauth/request_token',
		'access_token_url' => 'http://api.twitter.com/oauth/access_token',
		'authorize_url' => 'https://api.twitter.com/oauth/authorize',
	);
	return $config;
}
?>