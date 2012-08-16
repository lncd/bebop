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

/*
 * '$extension' controls content on this page and is set to whatever admin-settings.php file is being viewed.
 * i.e. if you extension name is 'my_extension', the value of $extension will be 'my_extension'.
 *  Make sure the extension name is in lower case.
 */
$extension = bebop_extensions::get_extension_config_by_name( strtolower( $extension ) );

/*
 * update section - if you add more parameters, don't forget to update them here.
 */
if ( isset( $_POST['submit'] ) ) {
	$success = true;
	if ( isset( $_POST['bebop_' . $extension['name'] . '_consumer_key'] ) ) {
		bebop_tables::update_option( 'bebop_' . $extension['name'] . '_consumer_key', trim( $_POST['bebop_' . $extension['name'] . '_consumer_key'] ) );
	}
	if ( isset( $_POST['bebop_' . $extension['name'] . '_consumer_secret'] ) ) {
		bebop_tables::update_option( 'bebop_' . $extension['name'] . '_consumer_secret', trim( $_POST['bebop_' . $extension['name'] . '_consumer_secret'] ) );
	}
	if ( isset( $_POST['bebop_' . $extension['name'] . '_maximport'] ) ) {
		bebop_tables::update_option( 'bebop_' . $extension['name'] . '_maximport', trim( $_POST['bebop_' . $extension['name'] . '_maximport'] ) );
	}
	
	/*rss stuff, dont touch */
	if ( isset( $_POST['bebop_' . $extension['name'] . '_rss_feed'] ) ) {
		if ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on' ) {
			bebop_tables::update_option( 'bebop_' . $extension['name'] . '_rss_feed', trim( $_POST['bebop_' . $extension['name'] . '_rss_feed'] ) );
		}
		else {
			$success = 'RSS feeds cannot be modified while the extension is not enabled.';
		}
	}
	else {
		bebop_tables::update_option( 'bebop_' . $extension['name'] . '_rss_feed', '' );
	}

	if ( $success == true ) {
		echo '<div class="bebop_success_box">Settings Saved.</div>';
	}
	else {
		echo '<div class="bebop_error_box">' . ucfirst( $success ) . '</div>';
	}
}

/*
 * Mechanics to remove a user from your extension is already provided - you do not need to modify this.
 */
if ( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim( $_GET['reset_user_id'] );
	bebop_tables::remove_user_from_provider( $user_id, $extension['name'] );
	
	echo '<div class="bebop_success_box">User has been removed.</div>';
}
//Include the admin menu.
include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class='postbox center_margin margin-bottom_22px'>
		<h3><?php echo $extension['display_name']; ?> Settings</h3>
		<div class="inside">
			<p>Settings for the <?php echo $extension['display_name']; ?> extension.</p>
			<p>To pull OER content from some providers, the importer settings need to be configured correctly for some extensions. or example, 'API Tokens', and 'API secrets' may be required for API based sources, but not for RSS based sources.</p>
			<p>By default, RSS feeds are available for each extension in bebop, and are automaticlly generated when an extension is active. You can turn the rss feeds off by simply unchecking the 'enabled' option of the RSS feed settings below. Please note
				that RSS feeds will only be available when the extension is active.</p>
		</div>
	</div>
	<form class='bebop_admin_form' method='post'>
		<fieldset>
			<span class='header'><?php echo $extension['display_name']; ?> Importer Settings</span>
			<label for='bebop_<?php echo $extension['name']; ?>_consumer_key'><?php echo $extension['display_name']; ?> API Token:</label>
			<input type='text' id='bebop_<?php echo $extension['name']; ?>_consumer_key' name='bebop_<?php echo $extension['name']; ?>_consumer_key' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_key' ); ?>' size='50'>
			
			<label for='bebop_<?php echo $extension['name']; ?>_consumer_secret'><?php echo $extension['display_name']; ?> API Secret:</label>
			<input type='text' id='bebop_<?php echo $extension['name']; ?>_consumer_secret' name='bebop_<?php echo $extension['name']; ?>_consumer_secret' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_consumer_secret' ); ?>' size='50'>
			
			<label for='bebop_<?php echo $extension['name']; ?>_maximport'>Imports per day (blank = unlimited):</label>
			<input type='text' id='bebop_<?php echo $extension['name']; ?>_maximport' name='bebop_<?php echo $extension['name']; ?>_maximport' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_maximport' ); ?>' size='5'>
		</fieldset>
		
		<fieldset>
			<span class='header'><?php echo $extension['display_name']; ?> RSS Settings</span>
			<?php
			if ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on' ) {
				echo "<label for='bebop_" . $extension['name'] . "_rss_feed'>RSS Enabled:</label><input id='bebop_" .$extension['name'] . "_rss_feed' name='bebop_".$extension['name'] . "_rss_feed' type='checkbox'";
				if ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_rss_feed' ) == 'on' ) {
					echo 'CHECKED';
				}
				echo '>';
			}
			else {
				echo '<p>RSS feeds cannot be enabled because ' . $extension['display_name'] . ' is not an active extension.</p>';
			}
			?>
		</fieldset>
		
		<div class='button_container'><button id='submit' name='submit'>Save Changes</button></div>
	</form>
	<?php
	$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_' . $extension['name'] . '_active_for_user' );
	if ( count( $user_metas ) > 0 ) {
		?>
		<table class='bebop_settings_table'>
			<tr class='nodata'>
				<th colspan='5'><?php echo $extension['display_name']; ?> Users</th>
			</tr>
			
			<tr class='nodata'>
				<td class='bold'>User ID</td>
				<td class='bold'>Username</td>
				<td class='bold'>User email</td>
				<td class='bold'><?php echo $extension['display_name']; ?> name</td>
				<td class='bold'>Options</td>
			</tr>
			<?php
			/*
			 * Loops through each user and prints their details to the screen.
			 */
			foreach ( $user_metas as $user ) {
				$this_user = get_userdata( $user->user_id );
				echo '<tr>
					<td>' . bebop_tables::sanitise_element( $user->user_id ) . '</td>
					<td>' . bebop_tables::sanitise_element( $this_user->user_login ) . '</td>
					<td>' . bebop_tables::sanitise_element( $this_user->user_email ) . '</td>
					<td>' . bebop_tables::sanitise_element( bebop_tables::get_user_meta_value( $user->user_id, 'bebop_' . $extension['name'] . '_username' ) ) . "</td>
					<td><a href='?page=bebop_oer_providers&provider=" . $extension['name'] . "&reset_user_id=" . bebop_tables::sanitise_element( $user->user_id ) . "'>Reset User</a></td>
				</tr>";
			}
		?>
		<!-- <End bebop_table -->
		</table>
		<?php
	}
	else {
		echo 'No users found for the ' . $extension['display_name'] . ' extension.';
	}
	?>
<!-- End bebop_admin_container -->
</div>
