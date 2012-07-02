<?php

global $bp;

if( isset( $_GET['reset'] ) ) {
	if ( $_GET['reset']  == 'true' ) {
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_oauth_token');
		bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_oauth_token_temp');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_oauth_token_secret');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_oauth_token_secret_temp');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_mention');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_synctoac');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_synctoac');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_filtermentions');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_filtergood');
	    bebop_tables::remove_user_meta($bp->loggedin_user->id, 'twitter_filterbad');
	}
}

if ( isset( $_GET['oauth_token'] ) ) {
	
    //Handle the oAuth requests
    $OAuth = new bebop_oauth();
    $OAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
    $OAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
    $OAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
    
    $OAuth->setParameters(array('oauth_verifier' => $_GET['oauth_verifier']));
    $OAuth->setCallbackUrl($bp->loggedin_user->domain . BP_XPROFILE_SLUG.'/bebop-oers/?oer=');
    $OAuth->setConsumerKey(bebop_tables::get_option_value("twitter_consumer_key"));
    $OAuth->setConsumerSecret(bebop_tables::get_option_value("twitter_consumer_secret"));
    $OAuth->setRequestToken(bebop_tables::get_user_meta_value($bp->loggedin_user->id,'twitter_oauth_token_temp'));
    $OAuth->setRequestTokenSecret(bebop_tables::get_user_meta_value($bp->loggedin_user->id,'twitter_oauth_token_secret_temp'));
    
    $accessToken = $OAuth->accessToken();
   
    bebop_tables::update_user_meta($bp->loggedin_user->id,'twitter_oauth_token',''.$accessToken['oauth_token'].'');
    bebop_tables::update_user_meta($bp->loggedin_user->id,'twitter_oauth_token_secret',''.$accessToken['oauth_token_secret'].'');
    bebop_tables::update_user_meta($bp->loggedin_user->id,'twitter_synctoac', 1);

    //for other plugins
    do_action('bebop__activated');

  }

if ( $_POST ) {
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'twitter_synctoac', $_POST['twitter_synctoac']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'twitter_filtermentions', $_POST['twitter_filtermentions']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'twitter_filtergood', $_POST['twitter_filtergood']);
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'twitter_filterbad', $_POST['twitter_filterbad']);

    echo '<div class="buddystream_message">Settings Saved</div>';
}

//put some options into variables
$twitter_synctoac       = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_synctoac');
$twitter_filtermentions = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_filtermentions');
$twitter_filtergood     = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_filtergood');
$twitter_filterbad      = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_filterbad');

if ( ( bebop_tables::get_option_value('bebop_twitter_provider') == 'on') && ( bebop_tables::check_option_exists('twitter_consumer_key') ) ) {
	if ( bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_oauth_token') ){
	    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'profile/bebop-oers/?oer=" method="post">
	    <h3> Settings</h3>';
	    
			echo '<br/><h5>Sync tweets to activity stream</h5>
			<input type="radio" name="twitter_synctoac" id="twitter_synctoac" value="1"';  if ($twitter_synctoac == 1) { echo 'checked'; } echo '>
			<label for="yes">Yes</label>
			
			<input type="radio" name="twitter_synctoac" id="twitter_synctoac" value="0"'; if ($twitter_synctoac == 0) { echo 'checked'; } echo '>
			<label for="no">No</label>
			
			<br>
			
			<br><h5>Filters</h5>
			User Settings<br>
			
			<br><h5>
			good filter (comma seperation)</h5>
			<input type="text" name="twitter_filtergood" value="' . $twitter_filtergood . '" size="50">
			
			<br><h5>bad filter (comma seperagtion)</h5>
			<input type="text" name="twitter_filterbad" value="' . $twitter_filterbad . '" size="50">';
		
	    echo '<br><br>';     
	    
	    echo '<input type="submit" value="Save Settings">';
	    
	    if( bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'twitter_oauth_token') ) {
	        echo '<a href="?oer=&reset=true">Remove sync</a>';
		}
		echo '</form>';
	}
	else {
		echo '<h3> setup</h3>
		You may setup you  intergration over here.<br/>
		Before you can begin using  with this site you must authorise on  by clicking the link below.<br/><br/>';
		
		//oauth
		$OAuth = new bebop_oauth();
		$OAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
		$OAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
		$OAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
		$OAuth->setCallbackUrl($bp->loggedin_user->domain . 'bebop-oers/?oer=');
		$OAuth->setConsumerKey(bebop_tables::get_option_value("twitter_consumer_key"));
		$OAuth->setConsumerSecret(bebop_tables::get_option_value("twitter_consumer_secret"));
		 
		//get requesttoken and save it for later use.
		$requestToken = $OAuth->requestToken();
	
		$OAuth->setRequestToken($requestToken['oauth_token']);
		$OAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);
		 
		bebop_tables::update_user_meta($bp->loggedin_user->id,'twitter_oauth_token_temp','' . $requestToken['oauth_token'].'');
		bebop_tables::update_user_meta($bp->loggedin_user->id,'twitter_oauth_token_secret_temp','' . $requestToken['oauth_token_secret'].'');
		
		//get the redirect url for the user
		$redirectUrl = $OAuth->getRedirectUrl();
		if( $redirectUrl ) {
			echo '<a href="' . $redirectUrl . '">Start authorisation</a><br/><br/>';
		}
		else{
			echo 'authentication is all broken :(';
		}
	}
}//End if ( ( bebop_tables::get_option_value('bebop_twitter_on') == true) && ( bebop_tables::check_option_exists('twitter_consumer_key') ) ) {
else {
	echo 'Twitter has not yet been configured. Please contact the admin to make sure twitter is enables as an OER provider.';
}