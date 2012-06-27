<?php

global $bp;

if( (isset( $_GET['reset'] )) && ( $_GET['reset']  == 'true' ) ) {
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_token');
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret');
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret_temp');
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_token_temp');
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_mention');
    bebop_tables::remove_user_meta($bp->loggedin_user->id,'tweetstream_synctoac');
    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac');
    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions');
    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood');
    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad');
}

if ( isset( $_GET['oauth_token'] ) ) {
    
    //Handle the oAuth requests
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
    
    $buddystreamOAuth->setParameters(array('oauth_verifier' => $_GET['oauth_verifier']));
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/buddystream-networks/?network=twitter');
    $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
    $buddystreamOAuth->setRequestToken(get_user_meta($bp->loggedin_user->id,'tweetstream_token_temp', 1));
    $buddystreamOAuth->setRequestTokenSecret(get_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret_temp', 1));
    
    $accessToken = $buddystreamOAuth->accessToken();
   
    bebop_tables::update_user_meta($bp->loggedin_user->id,'tweetstream_token',''.$accessToken['oauth_token'].'');
    bebop_tables::update_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret',''.$accessToken['oauth_token_secret'].'');
    bebop_tables::update_user_meta($bp->loggedin_user->id,'tweetstream_synctoac', 1);

    //for other plugins
    do_action('bebop_twitter_activated');

  }

if ( $_POST ) {
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac', $_POST['tweetstream_synctoac']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions', $_POST['tweetstream_filtermentions']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood', $_POST['tweetstream_filtergood']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad', $_POST['tweetstream_filterbad']);

    //achievements plugins
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'tweetstream_achievements', $_POST['tweetstream_achievements']);

    echo '<div class="buddystream_message">Settings Saved</div>';
}

//put some options into variables
$tweetstream_synctoac       = bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac');
$tweetstream_filtermentions = bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions');
$tweetstream_filtergood     = bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_filtergood');
$tweetstream_filterbad      = bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_filterbad');

if ( bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_token') ) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=twitter" method="post">
    <h3>Twitter Settings</h3>';
    
    if ( ! get_option('tweetstream_user_settings_syncbp')) {
        echo 'no settings available';
	}
	else {
		echo '<br/><h5>Synt tweets to activity stream</h5>
		<input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="1"';  if ($tweetstream_synctoac == 1) { echo 'checked'; } echo '>
		<label for="yes">Yes</label>/label>
		
		<input type="radio" name="tweetstream_synctoac" id="tweetstream_synctoac" value="0"'; if ($tweetstream_synctoac == 0) { echo 'checked'; } echo '>
		<label for="no">No</label>
		
		<br>
		
		<br><h5>Filters</h5>
		User Settings<br>
		
		<br><h5>
		good filter (comma seperation)</h5>
		<input type="text" name="tweetstream_filtergood" value="' . $tweetstream_filtergood . '" size="50">
		
		<br><h5>bad filter (comma seperagtion)</h5>
		<input type="text" name="tweetstream_filterbad" value="' . $tweetstream_filterbad . '" size="50">';
	}
    echo '<br><br>';     
    
    echo '<input type="submit" class="buddystream_save_button" value="Save Settings">';
    
    if( bebop_tables::get_user_meta($bp->loggedin_user->id, 'tweetstream_token') ) {
        echo '<a href="?network=twitter&reset=true" class="buddystream_reset_button">Remove twitter sync</a>';
	}
	echo '</form>';
}
else {
     
	echo '<h3>Twitter setup</h3>
	You may setup you twitter intergration over here.<br/>
	Before you can begin using Twitter with this site you must authorize on Twitter by clicking the link below.<br/><br/>';
	
	//oauth
	$buddystreamOAuth = new BuddyStreamOAuth();
	$buddystreamOAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
	$buddystreamOAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
	$buddystreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
	$buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/bebop-oers/?oer=twitter');
	$buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
	$buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
	 
	//get requesttoken and save it for later use.
	$requestToken = $buddystreamOAuth->requestToken();
	$buddystreamOAuth->setRequestToken($requestToken['oauth_token']);
	$buddystreamOAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);
	 
	bebop_tables::update_user_meta($bp->loggedin_user->id,'tweetstream_token_temp','' . $requestToken['oauth_token'].'');
	bebop_tables::update_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret_temp','' . $requestToken['oauth_token_secret'].'');
	
	//get the redirect url for the user
	$redirectUrl = $buddystreamOAuth->getRedirectUrl();
	if( $redirectUrl ) {
		echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">Start authorisaion</a><br/><br/>';
	}
	else{
		echo 'authentication is all broken :(';
	}
}