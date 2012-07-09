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

	echo '<div>Settings Saved</div>';
}
//remove the user
if( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim($_GET['reset_user_id']);
	bebop_tables::remove_user_from_provider($user_id, 'twitter');
}
?>

<form method="post" action="">
	<table class="bebop_settings_table">
	<th colspan='2'>Twitter Settings</th>
		<tr>
			<td>Twitter API Token</td>
			<td><input type="text" name="bebop_twitter_consumer_key" value="<?php echo bebop_tables::get_option_value('bebop_twitter_consumer_key'); ?>" size="50"></td>
		</tr>
		
		<tr>
			<td>Twitter API Secret:</td>
			<td><input type="text" name="bebop_twitter_consumer_secret" value="<?php echo bebop_tables::get_option_value('bebop_twitter_consumer_secret'); ?>" size="50"></td>
		</tr>
        
        <tr>
			<td>Maximum amount of imports</td>
			<td><input type="text" name="bebop_twitter_maximport" value="<?php echo bebop_tables::get_option_value('bebop_twitter_maximport'); ?>" size="5" /></td>
		</tr>
		
	</table>
	<p class='submit'><input type='submit' value='Save Changes'></p>
</form>

<div class="postbox width_98 margin-bottom_22px">
	<h3>Twitter Log</h3>
	<div class="inside">
		Users currently using twitter
	</div>
</div>
<div class="clear"></div>
	
<table class='bebop_table'>
	<tr class='nodata'>
		<th>User ID</th>
		<th>Username</th>
		<th>User email</th>
		<th>Twitter name</th>
		<th>Options</th>
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

