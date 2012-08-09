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
$extension = strtolower( $extension );

/*
 * update section - if you add more parameters, don't forget to update them here.
 */
if ( isset( $_POST['submit'] ) ) {
	bebop_tables::update_option( 'bebop_' . $extension . '_consumer_key', trim( $_POST['bebop_' . $extension . '_consumer_key'] ) );
	bebop_tables::update_option( 'bebop_' . $extension . '_consumer_secret', trim( $_POST['bebop_' . $extension . '_consumer_secret'] ) );
	bebop_tables::update_option( 'bebop_' . $extension . '_maximport', trim( $_POST['bebop_' . $extension . '_maximport'] ) );

	echo '<div class="bebop_success_box">Settings Saved.</div>';
}

/*
 * Mechanics to remove a user from your extension is already provided - you do not need to modify this.
 */
if ( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim( $_GET['reset_user_id'] );
	bebop_tables::remove_user_from_provider( $user_id, $extension );
	
	echo '<div class="bebop_success_box">User has been removed.</div>';
}
//Include the admin menu.
include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class='postbox center_margin margin-bottom_22px'>
		<h3><?php echo ucfirst( $extension ); ?> Settings</h3>
		<div class="inside">
			Settings required for <?php echo ucfirst( $extension ); ?> syncronisation.
		</div>
	</div>
	<form class='bebop_admin_form' method='post'>
		<fieldset>
			<span class='header'><?php echo ucfirst( $extension ); ?> Settings</span>
			<label for='bebop_<?php echo $extension; ?>_consumer_key'><?php echo ucfirst( $extension ); ?> API Token:</label>
			<input type='text' id='bebop_<?php echo $extension; ?>_consumer_key' name='bebop_<?php echo $extension; ?>_consumer_key' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension . '_consumer_key' ); ?>' size='50'>
			
			<label for='bebop_<?php echo $extension; ?>_consumer_secret'><?php echo ucfirst( $extension ); ?> API Secret:</label>
			<input type='text' id='bebop_<?php echo $extension; ?>_consumer_secret' name='bebop_<?php echo $extension; ?>_consumer_secret' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension . '_consumer_secret' ); ?>' size='50'>
			
			<label for='bebop_<?php echo $extension; ?>_maximport'>Imports per day (blank = unlimited):</label>
			<input type='text' id='bebop_<?php echo $extension; ?>_maximport' name='bebop_<?php echo $extension; ?>_maximport' value='<?php echo bebop_tables::get_option_value( 'bebop_' . $extension . '_maximport' ); ?>' size='5'>
		</fieldset>
		<div class='button_container'><button id='submit' name='submit'>Save Changes</button></div>
	</form>
	<?php
	$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_' . $extension . '_username' );
	if ( count( $user_metas ) > 0 ) {
		?>
		<table class='bebop_settings_table'>
			<tr class='nodata'>
				<th colspan='5'><?php echo ucfirst( $extension ); ?> Users</th>
			</tr>
			
			<tr class='nodata'>
				<td class='bold'>User ID</td>
				<td class='bold'>Username</td>
				<td class='bold'>User email</td>
				<td class='bold'><?php echo ucfirst( $extension ); ?> name</td>
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
					<td>' . bebop_tables::sanitise_element( bebop_tables::get_user_meta_value( $user->user_id, 'bebop_' . $extension . '_username' ) ) . "</td>
					<td><a href='?page=bebop_oer_providers&provider=" . $extension . "&reset_user_id=" . bebop_tables::sanitise_element( $user->user_id ) . "'>Reset User</a></td>
				</tr>";
			}
		?>
		<!-- <End bebop_table -->
		</table>
		<?php
	}
	else {
		echo 'No users found for the ' . ucfirst( $extension ) . ' extension.';
	}
	?>
<!-- End bebop_admin_container -->
</div>
