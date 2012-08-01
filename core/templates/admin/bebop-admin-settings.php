<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css'>
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">
<?php

if ( ! empty( $_POST['bebop_general_crontime'] ) ) {
	bebop_tables::update_option( 'bebop_general_crontime', trim( strip_tags( strtolower( $_POST['bebop_general_crontime'] ) ) ) );
	
	//Stops the cron
	wp_clear_scheduled_hook( 'bebop_cron' );
	
	//Re-activate with new time.
	wp_schedule_event( time(), 'secs', 'bebop_cron' );
	
	//Display that changes have been made.
	echo '<div class="bebop_success_box">Settings Saved.</div>';
}

include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' );
?>
<div id='bebop_admin_container'>
	<div class='postbox center_margin margin-bottom_22px'>
		<h3>Bebop Settings</h3>
		<div class="inside">
			General settings can be modified here.
		</div>
	</div>
	<form class='bebop_admin_form' method='post'>
		<fieldset>
			<span class='header'>Bebop Settings</span>
			<label for='bebop_general_crontime'>Cron time (in seconds):</label>
			<input type='text' id='bebop_general_crontime' name='bebop_general_crontime' value="<?php echo bebop_tables::get_option_value( 'bebop_general_crontime' ); ?>" size='5'>
			<div class="clear"></div>
		</fieldset>
		<div class='button_container'><button id='submit' name='submit'>Save Changes</button></div>	
	</form>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->