<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . "/bebop/core/templates/admin/bebop_admin_menu.php" ); ?>
<div id='bebop_admin_container'>
<?php
global $bp;

if ( $_POST ) {
	bebop_tables::update_option('tweetstream_consumer_key', trim($_POST['tweetstream_consumer_key']));
	bebop_tables::update_option('tweetstream_consumer_secret', trim($_POST['tweetstream_consumer_secret']));

	echo '<div>Settings Saved</div>';
}
?>

<form method="post" action="">
	<table class="bebop_settings_table">
	<th colspan='2'>Twitter Settings</th>
		<tr>
			<td>Twitter API Token</td>
			<td><input type="text" name="tweetstream_consumer_key" value="<?php echo bebop_tables::get_option('tweetstream_consumer_key'); ?>" size="50"></td>
		</tr>
		
		<tr>
			<td>Twitter API Secret:</td>
			<td><input type="text" name="tweetstream_consumer_secret" value="<?php echo bebop_tables::get_option('tweetstream_consumer_secret'); ?>" size="50"></td>
		</tr>
	</table>
	<p class='submit'><input type='submit' class='button-primary' value='Save Changes'></p>
</form>
<!-- End bebop_admin_container -->
</div>