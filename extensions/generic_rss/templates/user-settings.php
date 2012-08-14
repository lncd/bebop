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
if ( ( ! empty( $_POST['bebop_' . $extension['name'] . '_newfeedname'] ) ) && 
	( ! empty( $_POST['bebop_' . $extension['name'] . '_newfeedurl'] ) ) ) {
	//Updates the channel name.
	
	$found_http = strpos( $_POST['bebop_' . $extension['name'] . '_newfeedurl'], '://' );
	if ( ! $found_http ) {
		$insert_url = 'http://' . $_POST['bebop_' . $extension['name'] . '_newfeedurl'];
	}
	else {
		$insert_url = $_POST['bebop_' . $extension['name'] . '_newfeedurl'];
	}
	bebop_tables::add_user_meta( $bp->loggedin_user->id, $extension['name'], $_POST['bebop_' . $extension['name'] . '_newfeedname'], $insert_url );
	echo 'feed saved';
}
if ( isset( $_POST['bebop_' . $extension['name'] . '_active_for_user'] ) ) {
	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_active_for_user', $_POST['bebop_' . $extension['name'] . '_active_for_user'] );
	echo 'settings saved';
}

//delete a user's feed
if ( isset( $_GET['delete_feed'] ) ) {
	$check_feed = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, $_GET['delete_feed'] );
	if ( ! empty( $check_feed ) ) {
		$check_http = strpos( $check_feed, '://' );
		if ( $check_http ) {
			bebop_tables::remove_user_meta( $bp->loggedin_user->id, $_GET['delete_feed'] );
			echo 'feed deleted';
		}
	}
}

$active = 'bebop_' . $extension['name'] . '_active_for_user';																//the active boolean name
$$active = bebop_tables::get_user_meta_value( $bp->loggedin_user->id, 'bebop_' . $extension['name'] . '_active_for_user' );	//the value of the boolean

if ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on' ) {
	echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'bebop-oers/providers/?provider=' . $extension['name'] . '" method="post">
	<h3>' . ucfirst( $extension['name'] ) . ' Settings</h3>';
	echo '<h5>Enable ' . ucfirst( $extension['name'] ) . ' import?</h5>
	<input type="radio" name="bebop_' . $extension['name'] . '_active_for_user" id="bebop_' . $extension['name'] . '_active_for_user" value="1"';  if ( $$active == 1 ) {
		echo 'checked';
	} echo '>
	<label for="yes">Yes</label>
	<input type="radio" name="bebop_' . $extension['name'] . '_active_for_user" id="bebop_' . $extension['name'] . '_active_for_user" value="0"'; if ( $$active == 0 ) {
		echo 'checked';
	} echo '>
	<label for="no">No</label><br>';
	
	echo '<label for="bebop_' . $extension['name'] . '_newfeedname">New Feed Name:</label>
	<input type="text" name="bebop_' . $extension['name'] . '_newfeedname" size="50"><br>
	
	<label for="bebop_' . $extension['name'] . '_newfeedurl">New Feed URL:</label>
	<input type="text" name="bebop_' . $extension['name'] . '_newfeedurl" size="75"><br>
	
	<div class="button_container"><input type="submit" class="standard_button" value="Save Settings"></div>
	</form>';
	//table of user feeds
	$user_feeds = bebop_tables::get_user_generic_feeds( $bp->loggedin_user->id );
	if ( count( $user_feeds ) > 0 ) {
		echo '<h3>Your generic feeds</h3>';
		echo '<table class="bebop_user_table">
				<tr class="nodata">
					<th>Feed Name</th>
					<th>Feed URL</th>
					<th>Options</th>
				</tr>';
		foreach ( $user_feeds as $user_feed ) {
			echo '<tr>
				<td>' . bebop_tables::sanitise_element( $user_feed->meta_name ) . '</td>
				<td>' . bebop_tables::sanitise_element( $user_feed->meta_value ) . '</td>
				<td><a href="?provider=' . $extension['name'] . '&delete_feed=' . $user_feed->meta_name . '">Delete Feed</a></td>
			</tr>';
		}
		echo '</table>';
	}
}
?>
