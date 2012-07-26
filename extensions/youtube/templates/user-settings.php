<?php
if ( isset( $_GET['reset'] ) ) {
	//Removes the channel name.
	bebop_tables::remove_user_meta( $bp->loggedin_user->id, 'bebop_youtube_username' );
}


if ( isset( $_POST ) ) {
	//Updates the channel name.
	bebop_tables::update_user_meta( $bp->loggedin_user->id, 'youtube', 'bebop_youtube_username', $_POST['bebop_youtube_username'] );
}

	$bebop_youtube_username = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_youtube_username' );
	if ( $bebop_youtube_username ) {
		do_action( 'bebop_youtube_activated' );
	}
?>
<form id='settings_form' action='<?php echo  $bp->loggedin_user->domain ?>bebop-oers/providers/?provider=youtube' method='post'>
	<h3>Youtube Settings</h3>
	Youtube username<br/>
	<input type='text' name='bebop_youtube_username' value='<?php echo $bebop_youtube_username; ?>' size='50' ><br/><br/>
	<input type='submit' class='standard_button' value='Save Channel'>
</form>
<?php
if ( ! empty( $bebop_youtube_username ) ) {
	echo '<br><a class="standard_button provider_button" href="?oer=youtube&reset=true">Remove Channel</a>';
}
?>