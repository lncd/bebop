<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . "/bebop/core/templates/admin/bebop_admin_menu.php" ); ?>
<div id='bebop_admin_container'>
<?php
global $bp;

//updates
if ( $_POST ) {
	bebop_tables::update_option('bebop_twitter_consumer_key', trim($_POST['bebop_twitter_consumer_key']));
	bebop_tables::update_option('bebop_twitter_consumer_secret', trim($_POST['bebop_twitter_consumer_secret']));
	bebop_tables::update_option('bebop_twitter_maximport', trim($_POST['bebop_twitter_maximport']));

	echo '<div class="bebop_green_box">Settings Saved.</div>';
}
//remove the user
if( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim($_GET['reset_user_id']);
	bebop_tables::remove_user_from_provider($user_id, 'twitter');
	
	echo '<div class="bebop_green_box">User has been removed.</div>';
}
?>

<form method="post" class='bebop_admin_form'>
	<fieldset>
		<label>Twitter API Token</label>
		<input type="text" name="bebop_twitter_consumer_key" value="<?php echo bebop_tables::get_option_value('bebop_twitter_consumer_key'); ?>" size="50">

		<label>Twitter API Secret:</label>
		<input type="text" name="bebop_twitter_consumer_secret" value="<?php echo bebop_tables::get_option_value('bebop_twitter_consumer_secret'); ?>" size="50">

		<label>Maximum amount of imports</label>
		<input type="text" name="bebop_twitter_maximport" value="<?php echo bebop_tables::get_option_value('bebop_twitter_maximport'); ?>" size="5">

	</fieldset>
	<input type='submit' value='Save Changes'>
</form>


<table class='bebop_settings_table'>
	<tr class='nodata'>
		<th colspan='5'>Twitter users</th>
	</tr>
	
	<tr class='nodata'>
		<td>User ID</td>
		<td>Username</td>
		<td>User email</td>
		<td>Twitter name</td>
		<td>Options</td>
	</tr>
	<?php
	$user_metas = bebop_tables::get_user_ids_from_meta_name('bebop_twitter_screenname');	
	
	foreach( $user_metas as $user ) {
		$this_user = get_userdata($user->user_id);
		echo "<tr>
			<td>" . bebop_tables::sanitise_element($user->user_id) . "</td>
			<td>" . bebop_tables::sanitise_element($this_user->user_login) . "</td>
			<td>" . bebop_tables::sanitise_element($this_user->user_email) . "</td>
			<td>" . bebop_tables::sanitise_element(bebop_tables::get_user_meta_value( $user->user_id, 'bebop_twitter_screenname' ) ) . "</td>
			<td><a href='?page=bebop_twitter&reset_user_id=" . bebop_tables::sanitise_element($user->user_id) . "'>Reset User</a></td>
		</tr>";
	}
	?>
	<!-- <End bebop_table -->
</table>

<!-- End bebop_admin_container -->
</div>

