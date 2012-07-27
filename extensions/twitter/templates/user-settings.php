<?php
global $bp;

//resets the user's data
if ( isset( $_GET['reset'] ) ) {
	if ( $_GET['reset'] == 'true' ) {	
		bebop_tables::remove_user_from_provider( $bp->loggedin_user->id, 'twitter' );
	}
}

if ( isset( $_GET['oauth_token'] ) ) {
	//Handle the oAuth requests
	$OAuth = new bebop_oauth();
	$OAuth->set_request_token_url( 'http://api.twitter.com/oauth/request_token' );
	$OAuth->set_access_token_url( 'http://api.twitter.com/oauth/access_token' );
	$OAuth->set_authorize_url( 'https://api.twitter.com/oauth/authorize' );
	
	$OAuth->set_parameters( array( 'oauth_verifier' => $_GET['oauth_verifier'] ) );
	$OAuth->set_callback_url( $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=twitter' );
	$OAuth->set_consumer_key( bebop_tables::get_option_value( 'bebop_twitter_consumer_key' ) );
	$OAuth->set_consumer_secret( bebop_tables::get_option_value( 'bebop_twitter_consumer_secret' ) );
	$OAuth->set_request_token( bebop_tables::get_user_meta_value( $bp->loggedin_user->id,'bebop_twitter_oauth_token_temp' ) );
	$OAuth->set_request_token_secret( bebop_tables::get_user_meta_value( $bp->loggedin_user->id,'bebop_twitter_oauth_token_secret_temp' ) );
	
	$accessToken = $OAuth->access_token();
	
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_oauth_token', $accessToken['oauth_token'] );
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_oauth_token_secret', $accessToken['oauth_token_secret'] );
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_active_for_user', 1 );
	
	//for other plugins
	do_action( 'bebop_twitter_activated' );
}

if ( isset( $_POST['bebop_twitter_active_for_user'] ) ) {
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_active_for_user', $_POST['bebop_twitter_active_for_user'] );
	echo '<div class="bebop_message">Settings Saved</div>';
}

//put some options into variables
$bebop_twitter_active_for_user = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_twitter_active_for_user' );

if ( ( bebop_tables::get_option_value( 'bebop_twitter_provider' ) == 'on') && ( bebop_tables::check_option_exists( 'bebop_twitter_consumer_key' ) ) ) {
	if ( bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_twitter_oauth_token' ) ) {
		echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=twitter" method="post">
		<h3>Twitter Settings</h3>';
		
		echo '<h5>Enable Twitter import?</h5>
		<input type="radio" name="bebop_twitter_active_for_user" id="bebop_twitter_active_for_user" value="1"';  if ( $bebop_twitter_active_for_user == 1 ) {
			echo 'checked';
		} echo '>
		<label for="yes">Yes</label>
		<input type="radio" name="bebop_twitter_active_for_user" id="bebop_twitter_active_for_user" value="0"'; if ( $bebop_twitter_active_for_user == 0 ) {
			echo 'checked';
		} echo '>
		<label for="no">No</label><br>
		<div class="button_container"><input type="submit" class="standard_button" value="Save Settings"></div>';
			
		if ( bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_twitter_oauth_token' ) ) {
			echo '<div class="button_container"><a class="standard_button" href="?provider=twitter&reset=true">Remove Authorisation</a></div>';
		}
		echo '</form>';
	}
	else {
		echo '<h3> setup</h3>
		You may setup twitter intergration over here.
		Before you can begin using twitter with this site you must authorise on twitter by clicking the link below.';
		
		//oauth
		$OAuth = new bebop_oauth();
		$OAuth->set_request_token_url( 'http://api.twitter.com/oauth/request_token' );
		$OAuth->set_access_token_url( 'http://api.twitter.com/oauth/access_token' );
		$OAuth->set_authorize_url( 'https://api.twitter.com/oauth/authorize' );
		$OAuth->set_callback_url( $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=twitter' );
		$OAuth->set_consumer_key( bebop_tables::get_option_value( 'bebop_twitter_consumer_key' ) );
		$OAuth->set_consumer_secret( bebop_tables::get_option_value( 'bebop_twitter_consumer_secret' ) );
		
		//get the oauth token
		$requestToken = $OAuth->request_token();
		
		$OAuth->set_request_token( $requestToken['oauth_token'] );
		$OAuth->set_request_token_secret( $requestToken['oauth_token_secret'] );
		
		bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_oauth_token_temp','' . $requestToken['oauth_token'].'' );
		bebop_tables::update_user_meta( $bp->loggedin_user->id, 'twitter', 'bebop_twitter_oauth_token_secret_temp','' . $requestToken['oauth_token_secret'].'' );
		
		//get the redirect url for the user
		$redirectUrl = $OAuth->get_redirect_url();
		if ( $redirectUrl ) {
			echo '<div class="button_container"><a class="standard_button" href="' . $redirectUrl . '" class="standard_button">Start Authorisation</a></div>';
		}
		else {
			echo 'authentication is all broken :(';
		}
	}
}//End if ( ( bebop_tables::get_option_value( 'bebop_twitter_provider' ) == 'on') && ( bebop_tables::check_option_exists( 'bebop_twitter_consumer_key' ) ) ) {
else {
	echo 'Twitter has not yet been configured. Please contact the blog admin to make sure twitter is enables as an OER provider.';
}