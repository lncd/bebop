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
	<form class='bebop_admin_form' method='post'>
		<fieldset>  
			<legend><span class='header'>General Settings</span></legend>
			<label for='bebop_general_crontime'>Cron time (in seconds):</label>
			<input type='text' id='bebop_general_crontime' name='bebop_general_crontime' value="<?php echo bebop_tables::get_option_value( 'bebop_general_crontime' ); ?>" size='5'>
			<div class="clear"></div>
			<div class='button_container form_button_container'><button>Save Changes</button></div>	
		</fieldset>
	</form>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->