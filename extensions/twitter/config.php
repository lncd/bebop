<?php

/*Config file for an extension*/

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
		'data_api' => 'http://api.twitter.com/1/statuses/user_timeline.xml',
		'content_type' => 'tweet',
		'content_oembed' => false,
	);
	return $config;
}
?>