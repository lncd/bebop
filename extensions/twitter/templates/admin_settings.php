<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css'; ?>">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<div id='bebop_admin_container'>
<?php
global $bp;

$arraySwitches = array(
    'tweetstream_user_settings_syncbp',
    'buddystream_twitter_hide_sitewide',
    'buddystream_twitter_share'
);

	if ($_POST) {
		bebop_tables::update_option('tweetstream_consumer_key', trim($_POST['tweetstream_consumer_key']));
		bebop_tables::update_option('tweetstream_consumer_secret', trim($_POST['tweetstream_consumer_secret']));
		bebop_tables::update_option('buddystream_twitter_user_settings_maximport', trim(strip_tags(strtolower($_POST['buddystream_twitter_user_settings_maximport']))));
      
		if($_POST['tweetstream_consumer_key']){
			bebop_tables::update_option('buddystream_twitter_setup', true);
		}
      
		foreach($arraySwitches as $switch){
			bebop_tables::update_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
		}
		echo '<div>Settings Saved</div>';
	}
	?>

	<div class="bebop_admin_box">
		Settings
	</div>
	
	<form method="post" action="">
		<table class="buddystream_table" cellspacing="0">
		
		<tr class="header">
			<td colspan="2">Twitter API</td>
		</tr>
		  
		<tr>
			<td>Consumer key</td>
			<td><input type="text" name="tweetstream_consumer_key" value="<?php echo bebop_tables::get_option('tweetstream_consumer_key'); ?>" size="50" /></td>
		</tr>
		
		<tr class="odd">
			<td>Consumer secret key:</td>
			<td><input type="text" name="tweetstream_consumer_secret" value="<?php echo bebop_tables::get_option('tweetstream_consumer_secret'); ?>" size="50" /></td>
		</tr>
		
		<?php
		if( bebop_tables::get_option('tweetstream_consumer_key') && bebop_tables::get_option('tweetstream_consumer_secret')){ 
		
			echo " <tr class='header'>
				<td colspan='2'>User options</td>
			</tr>
			
			<tr>
				<td>Hide tweets on the sidewide activity stream?</td>
				<td><input class='switch icons' type='checkbox' name='buddystream_twitter_hide_sitewide' id='buddystream_twitter_hide_sitewide'/></td>
			</tr>
			
			<tr valign='top' class='odd'>
				<td><Allow users to sync Twitter to your site?</td>
				<td><input class='switch icons' type='checkbox' name='tweetstream_user_settings_syncbp' id='tweetstream_user_settings_syncbp'/></td>
			</tr>
			
			<tr valign='top'>
				<td>Maximum Tweets to be imported per user, per day (empty = unlimited tweets import):</td>
				<td><input type='text' name='buddystream_twitter_user_settings_maximport' value='<?php echo bebop_tables::get_option('buddystream_twitter_user_settings_maximport'); ?>' size='5' /></td>
			</tr>";
		
			if(bebop_tables::get_option('buddystream_sharebox') == 'on') { 
			
			echo "<tr class='header'>
				<td colspan='2'>Extra options</td>
			</tr>
			
			<tr>
				<td>Show Twitter share button?</td>
				<td><input class='switch icons' type='checkbox' name='buddystream_twitter_share' id='buddystream_twitter_share'/></td>
			</tr>";
			}   
		} 
		echo "</table>
			<p class='submit'><input type='submit' class='button-primary' value='Save Changes'></p>
		</form>";
		?>

</div>