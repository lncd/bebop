<?php
/*
 * IMPORTANT - PLEASE READ **************************************************************************
 * All the mechanics to control this plugin are automatically generated from the extension name.	*
 * You do not need to modify this page, unless you wish to add additional customisable parameters	*
 * for the extension. Removing/changing any of the pre defined functions will cause import errors,	*
 * and possible other unexpected or unwanted behaviour.												*
 * For information on bebop_tables:: functions, please see bebop/core/bebop-tables.php				*
 * **************************************************************************************************
 */
global $bp;
/*
 * '$extension' controls content on this page and is set to whatever admin-settings.php file is being viewed.
 * i.e. if you extension name is 'my_extension', the value of $extension will be 'my_extension'.
 * The extension has to exist if this page is being included.
 */
$extension = bebop_extensions::get_extension_config_by_name( strtolower( $_GET['provider'] ) );

/*
 * update section - if you add more parameters, don't forget to update them here.
 */
if ( isset( $_POST['submit'] ) ) {
	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_active_for_user', $_POST['bebop_' . $extension['name'] . '_active_for_user'] );
	echo '<div class="bebop_message">Settings Saved</div>';
}

//resets the user's data
if ( isset( $_GET['reset'] ) ) {
	if ( $_GET['reset'] == 'true' ) {	
		bebop_tables::remove_user_from_provider( $bp->loggedin_user->id, $extension['name'] );
	}
}

if ( isset( $_GET['oauth_token'] ) ) {
	//Handle the oAuth requests
	$OAuth = new bebop_oauth();
	$OAuth->set_request_token_url( $extension['request_token_url'] );
	$OAuth->set_access_token_url( $extension['access_token_url'] );
	$OAuth->set_authorize_url( $extension['authorize_url'] );
	
	$OAuth->set_parameters( array( 'oauth_verifier' => $_GET['oauth_verifier'] ) );
	$OAuth->set_callback_url( $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=' . $extension['name'] );
	$OAuth->set_consumer_key( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_key' ) );
	$OAuth->set_consumer_secret( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_secret' ) );
	$OAuth->set_request_token( bebop_tables::get_user_meta_value( $bp->loggedin_user->id,'bebop_' . $extension['name'] . '_oauth_token_temp' ) );
	$OAuth->set_request_token_secret( bebop_tables::get_user_meta_value( $bp->loggedin_user->id,'bebop_' . $extension['name'] . '_oauth_token_secret_temp' ) );
	
	$accessToken = $OAuth->access_token();
	
	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_oauth_token', $accessToken['oauth_token'] );
	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_oauth_token_secret', $accessToken['oauth_token_secret'] );
	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_active_for_user', 1 );
	
	//for other plugins
	do_action( 'bebop_' . $extension['name'] . '_activated' );
}

//put some options into variables
$variable_name = 'bebop_' . $extension['name'] . '_active_for_user';																//the name of the variable
$$variable_name = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_' . $extension['name'] . '_active_for_user' );	//the value of the variable

if ( ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on') && ( bebop_tables::check_option_exists( 'bebop_' . $extension['name'] . '_consumer_key' ) ) ) {
	if ( bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_' . $extension['name'] . '_oauth_token' ) ) {
		echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=' . $extension['name'] . '" method="post">
		<h3>' . ucfirst( $extension['name'] ) . ' Settings</h3>';
		
		echo '<h5>Enable ' . ucfirst( $extension['name'] ) . ' import?</h5>
		<input type="radio" name="bebop_' . $extension['name'] . '_active_for_user" id="bebop_' . $extension['name'] . '_active_for_user" value="1"';  if ( $$variable_name == 1 ) {
			echo 'checked';
		} echo '>
		<label for="yes">Yes</label>
		<input type="radio" name="bebop_' . $extension['name'] . '_active_for_user" id="bebop_' . $extension['name'] . '_active_for_user" value="0"'; if ( $$variable_name == 0 ) {
			echo 'checked';
		} echo '>
		<label for="no">No</label><br>
		<div class="button_container"><input type="submit" name="submit" class="standard_button" value="Save Settings"></div>';
			
		if ( bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_' . $extension['name'] . '_oauth_token' ) ) {
			echo '<div class="button_container"><a class="standard_button" href="?provider=' . $extension['name'] . '&reset=true">Remove Authorisation</a></div>';
		}
		echo '</form>';
	}
	else {
		echo '<h3> setup</h3>
		You may setup ' . ucfirst( $extension['name'] ) . ' intergration over here.
		Before you can begin using ' . ucfirst( $extension['name'] ) . ' with this site you must authorise on ' . ucfirst( $extension['name'] ) . ' by clicking the link below.';
		
		//oauth
		$OAuth = new bebop_oauth();
		$OAuth->set_request_token_url( $extension['request_token_url'] );
		$OAuth->set_access_token_url( $extension['access_token_url'] );
		$OAuth->set_authorize_url( $extension['authorize_url'] );
		$OAuth->set_callback_url( $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=' . $extension['name'] );
		$OAuth->set_consumer_key( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_key' ) );
		$OAuth->set_consumer_secret( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_secret' ) );
		
		//get the oauth token
		$requestToken = $OAuth->request_token();
		
		$OAuth->set_request_token( $requestToken['oauth_token'] );
		$OAuth->set_request_token_secret( $requestToken['oauth_token_secret'] );
		
		bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_oauth_token_temp','' . $requestToken['oauth_token'].'' );
		bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_oauth_token_secret_temp','' . $requestToken['oauth_token_secret'].'' );
		
		//get the redirect url for the user
		$redirectUrl = $OAuth->get_redirect_url();
		if ( $redirectUrl ) {
			echo '<div class="button_container"><a class="standard_button" href="' . $redirectUrl . '" class="standard_button">Start Authorisation</a></div>';
		}
		else {
			echo 'authentication is all broken :(';
		}
	}
}// if ( ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on') && ( bebop_tables::check_option_exists( 'bebop_' . $extension['name'] . '_consumer_key' ) ) ) {
else {
	echo ucfirst( $extension['name'] ) . ' has not yet been configured. Please contact the blog admin to make sure ' . ucfirst( $extension['name'] ) . ' is configured properly.';
}