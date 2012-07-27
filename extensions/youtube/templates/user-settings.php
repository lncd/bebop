<?php
if ( isset( $_GET['reset'] ) ) {
	bebop_tables::remove_user_meta( $bp->loggedin_user->id, 'bebop_youtube_username' );
}


if ( isset( $_POST['bebop_youtube_username'] ) ) {
	//Updates the channel name.
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'youtube', 'bebop_youtube_username', $_POST['bebop_youtube_username'] );
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'youtube', 'bebop_youtube_active_for_user', 1 );
	
	do_action( 'bebop_youtube_activated' );
}
if ( isset( $_POST['bebop_youtube_active_for_user'] ) ) {
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'youtube', 'bebop_youtube_active_for_user', $_POST['bebop_youtube_active_for_user'] );
}
$bebop_youtube_username = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_youtube_username' );
$bebop_youtube_active_for_user = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_youtube_active_for_user' );

echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=youtube" method="post">
<h3>Youtube Settings</h3>';
if ( ! empty( $bebop_youtube_username ) ) {
	echo '<h5>Enable Youtube import?</h5>
	<input type="radio" name="bebop_youtube_active_for_user" id="bebop_youtube_active_for_user" value="1"';  if ( $bebop_youtube_active_for_user == 1 ) {
		echo 'checked';
	} echo '>
	<label for="yes">Yes</label>
	<input type="radio" name="bebop_youtube_active_for_user" id="bebop_youtube_active_for_user" value="0"'; if ( $bebop_youtube_active_for_user == 0 ) {
		echo 'checked';
	} echo '>
	<label for="no">No</label><br>';
}

echo '<label for="bebop_youtube_username">Youtube Username:</label>
<input type="text" name="bebop_youtube_username" value="' . $bebop_youtube_username .'" size="50"><br>

<div class="button_container"><input type="submit" class="standard_button" value="Save Settings"></div>';
if ( ! empty( $bebop_youtube_username ) ) {
	echo '<div class="button_container"><a class="standard_button" href="?provider=youtube&reset=true">Remove Channel</a></div>';
}
echo '</form>'; 
?>
