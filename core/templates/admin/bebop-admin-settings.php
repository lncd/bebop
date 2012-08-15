<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">
<?php

if ( ! empty( $_POST['bebop_general_crontime'] ) ) {
	
	$crontime = bebop_tables::update_option( 'bebop_general_crontime', trim( strip_tags( strtolower( $_POST['bebop_general_crontime'] ) ) ) );
	wp_clear_scheduled_hook( 'bebop_cron' ); //Stops the cron
	if( $crontime > 0 ) {	//if cron time is > 0, reschedule the cron. If zero, do nto reschedule
		wp_schedule_event( time(), 'secs', 'bebop_cron' );//Re-activate with new time.
	}
	echo '<div class="bebop_success_box">Settings Saved.</div>'; //Display that changes have been made.
}
include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' );
?>
<div id='bebop_admin_container'>
	<div class='postbox center_margin margin-bottom_22px'>
		<h3>Bebop Settings</h3>
		<div class='inside'>
			General settings can be modified here.
		</div>
	</div>
	<form class='bebop_admin_form' method='post'>
		<fieldset>
			<span class='header'>Bebop Settings</span>
			<p>The WordPress cron runs the import script for the given timeframe. The default is set to 5 minutes. (300 seconds). The only issue with the WordPress cron is that it can only be activated when a page is accessed. So, if no-one was to visit the site for a long period of time,
			the importers might miss some content items. You should therefore use the WordPress cron only if you cannot use a traditional cron. Do not use both together.</p>
			<p>To use the traditional cron, add the following cron command to your webhosting cron lists, setting a timeframe of your choice, but we recommend that you do not go lower than 10 minutes.</p>
			<p>If you use a traditional cron, set the WordPress Cron time to '0'.</p>
			<label for='bebop_general_crontime'>WordPress Cron time (in seconds):</label>
			<input type='text' id='bebop_general_crontime' name='bebop_general_crontime' value='<?php echo bebop_tables::get_option_value( 'bebop_general_crontime' ); ?>' size='10'><br>
			
			<label for='traditional_cron'>Traditional Cron:</label>
			<input type='text' id='traditional_cron' value="wget <?php echo plugins_url() . '/bebop/import.php -O /dev/null -q'?>" size='75' READONLY>
			<div class="clear"></div>
		</fieldset>
		<div class='button_container'><button id='submit' name='submit'>Save Changes</button></div>	
	</form>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->