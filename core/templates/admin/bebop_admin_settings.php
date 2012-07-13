<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php
if ($_POST) {
   	bebop_tables::update_option('bebop_general_badword', trim(strip_tags(strtolower($_POST['bebop_general_badword']))));
   	bebop_tables::update_option('bebop_general_crontime', trim(strip_tags(strtolower($_POST['bebop_general_crontime']))));      
   	
   	//Stops the cron
	wp_clear_scheduled_hook( 'bebop_cron' );
	
	//Re-activate with new time.
	wp_schedule_event( time(), 'secs', 'bebop_cron' );
	
	//Display that changes have been made.
   	echo '<div class="bebop_success_box">Settings Saved.</div>';
}

include_once( WP_PLUGIN_DIR . "/bebop/core/templates/admin/bebop_admin_menu.php" ); ?>
<div id='bebop_admin_container'>
	<form method="post" class='bebop_admin_form'>
		<fieldset>  
			<legend><span class='header'>General Settings</span></legend>
			<label for='bebop_youtube_maximport'>The bad word filter:</label>
			<input type="text" id="bebop_youtube_maximport" name="bebop_general_badword" value="<?php echo bebop_tables::get_option_value('bebop_general_badword'); ?>" size="30"><br>	
			<label for='bebop_youtube_maximport'>Cron time (in seconds):</label>
			<input type="text" id="bebop_youtube_maximport" name="bebop_general_crontime" value="<?php echo bebop_tables::get_option_value('bebop_general_crontime'); ?>" size="5">
			<div class='bebop_button_container'><button>Save Changes</button></div>	
		</fieldset>	  
	</form>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->