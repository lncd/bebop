<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css'>
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php
global $bp;

//updates
if ( isset( $_POST['submit'] ) ){
	bebop_tables::update_option( 'bebop_twitter_consumer_key', trim( $_POST['bebop_twitter_consumer_key'] ) );
	bebop_tables::update_option( 'bebop_twitter_consumer_secret', trim( $_POST['bebop_twitter_consumer_secret'] ) );
	bebop_tables::update_option( 'bebop_twitter_maximport', trim( $_POST['bebop_twitter_maximport'] ) );

	echo '<div class="bebop_success_box">Settings Saved.</div>';
}
//remove the user
if ( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim( $_GET['reset_user_id'] );
	bebop_tables::remove_user_from_provider( $user_id, 'twitter' );
	
	echo '<div class="bebop_success_box">User has been removed.</div>';
}
include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
<form method='post' class='bebop_admin_form'>
	<fieldset>
		<legend><span class='header'>Twitter Settings</span></legend>
		<label for='bebop_twitter_consumer_key'>Twitter API Token:</label>
		<input type='text' id='bebop_twitter_consumer_key' name='bebop_twitter_consumer_key' value='<?php echo bebop_tables::get_option_value( 'bebop_twitter_consumer_key' ); ?>' size='50'>
		
		<label for='bebop_twitter_consumer_secret'>Twitter API Secret:</label>
		<input type='text' id='bebop_twitter_consumer_secret' name='bebop_twitter_consumer_secret' value='<?php echo bebop_tables::get_option_value( 'bebop_twitter_consumer_secret' ); ?>' size='50'>
		
		<label for='bebop_twitter_maximport'>Maximum amount of imports:</label>
		<input type='text' id='bebop_twitter_maximport' name='bebop_twitter_maximport' value='<?php echo bebop_tables::get_option_value( 'bebop_twitter_maximport' ); ?>' size='5'>
	<div class='bebop_button_container'><button id='submit' name='submit'>Save Changes</button></div>
	</fieldset>
</form>
<?php
$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_twitter_username' );
if ( count( $user_metas ) > 0 ) {
	?>
	<table class='bebop_settings_table'>
		<tr class='nodata'>
			<th colspan='5'>Twitter Users</th>
		</tr>
		
		<tr class='nodata'>
			<td class='bold'>User ID</td>
			<td class='bold'>Username</td>
			<td class='bold'>User email</td>
			<td class='bold'>Twitter name</td>
			<td class='bold'>Options</td>
		</tr>
		<?php		
		
		foreach ( $user_metas as $user ) {
			$this_user = get_userdata( $user->user_id );
			echo '<tr>
				<td>' . bebop_tables::sanitise_element( $user->user_id ) . '</td>
				<td>' . bebop_tables::sanitise_element( $this_user->user_login ) . '</td>
				<td>' . bebop_tables::sanitise_element( $this_user->user_email ) . '</td>
				<td>' . bebop_tables::sanitise_element( bebop_tables::get_user_meta_value( $user->user_id, 'bebop_twitter_screenname' ) ) . "</td>
				<td><a href='?page=bebop_twitter&reset_user_id=" . bebop_tables::sanitise_element( $user->user_id ) . "'>Reset User</a></td>
			</tr>";
		}
	?>
	<!-- <End bebop_table -->
	</table>
	<?php
}
else {
	echo '<div class="standard_class">No users found for this extension.</div>';
}
?>
<!-- End bebop_admin_container -->
</div>
