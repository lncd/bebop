<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . "/bebop/core/templates/bebop_admin_menu.php" ); ?>
<div id='bebop_admin_container'>
<?php
global $bp;

$arraySwitches = array(
    'tweetstream_user_settings_syncbp',
    'buddystream_twitter_hide_sitewide',
    'buddystream_twitter_share'
);

	if ( $_POST ) {
		bebop_tables::update_option('tweetstream_consumer_key', trim($_POST['tweetstream_consumer_key']));
		bebop_tables::update_option('tweetstream_consumer_secret', trim($_POST['tweetstream_consumer_secret']));
		if(! empty( $_POST['buddystream_twitter_user_settings_maximport'] ) ) {
			bebop_tables::update_option('buddystream_twitter_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_twitter_user_settings_maximport']))));
		}
      
		if( isset( $_POST['tweetstream_consumer_key'] ) ){
			bebop_tables::update_option('buddystream_twitter_setup', true);
		}
      
		foreach($arraySwitches as $switch){
			bebop_tables::update_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
		}
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
		
		<?php

		if( bebop_tables::get_option('tweetstream_consumer_key') && bebop_tables::get_option('tweetstream_consumer_secret') ) {
			?> 
		
			<tr class='header'>
				<td colspan='2'>User options</td>
			</tr>
			
			<tr>
				<td>Hide tweets on the sidewide activity stream?</td>
				<td><input class='switch icons' type='checkbox' name='buddystream_twitter_hide_sitewide' id='buddystream_twitter_hide_sitewide'></td>
			</tr>
			
			<tr>
				<td><Allow users to sync Twitter to your site?</td>
				<td><input class='switch icons' type='checkbox' name='tweetstream_user_settings_syncbp' id='tweetstream_user_settings_syncbp'></td>
			</tr>
			
			<tr valign='top'>
				<td>Maximum Tweets to be imported per user, per day (empty = unlimited tweets import):</td>

				<td><input type='text' name='buddystream_twitter_user_settings_maximport' value='" . bebop_tables::get_option('buddystream_twitter_user_settings_maximport') . "' size='5'></td>
			</tr>";
<?php
		
			if(bebop_tables::get_option('buddystream_sharebox') == 'on') { 

			
			echo "<tr class='header'>
				<td colspan='2'>Extra options</td>
			</tr>
			
			<tr>
				<td>Show Twitter share button?</td>
				<td><input class='switch icons' type='checkbox' name='buddystream_twitter_share' id='buddystream_twitter_share'></td>
			</tr>";
			}   
		} 
		echo "</table>
			<p class='submit'><input type='submit' class='button-primary' value='Save Changes'></p>
		</form>";
		?>
<!-- End bebop_admin_container -->
</div>