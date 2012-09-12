<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">
<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
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
			<p>The WordPress cron runs the import script for the given timeframe. The default is set to 10 minutes. (600 seconds). The only issue with the WordPress cron is that it can only be activated when a page is accessed. So, if no-one was to visit the site for a long period of time,
			the importers might miss some content items. You should therefore use the WordPress cron only if you cannot use a traditional cron. Do not use both together.</p>
			<p>To use the traditional cron, add the following cron command to your webhosting cron lists, setting a timeframe of your choice. </p>
			<p>If you use a traditional cron, set the WordPress Cron time to '0'.</p>
			
			<p><strong>New:</strong> As of version 1.1, a secondary cron was introduced. This allows the major import scripts to run at less frequency, while still allowing new users and new feeds to import data within 20 seconds.
				Therefore you should not need to run the cron any less than 10 minutes (600 seconds).</p>
			<label for='bebop_general_crontime'>WordPress Cron time (in seconds):</label>
			<input type='text' id='bebop_general_crontime' name='bebop_general_crontime' value='<?php echo bebop_tables::get_option_value( 'bebop_general_crontime' ); ?>' size='10'><br>
			
			<label for='traditional_cron'>Traditional Cron:</label>
			<input type='text' id='traditional_cron' value="wget <?php echo plugins_url() . '/bebop/import.php -O /dev/null -q'?>" size='75' READONLY>
			<div class="clear"></div>
		</fieldset>
		<input class='button-primary' type='submit' id='submit' name='submit' value='Save Changes'>
	</form>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->