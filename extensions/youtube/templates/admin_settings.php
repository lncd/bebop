<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css'; ?>">
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>



<?php

$arraySwitches = array(
					   'bebop_youtube_hide_sitewide',
   					   'bebop_youtube_album'
  					  );

if ($_POST) {
   	update_site_option('bebop_youtube_maximport', trim(strip_tags(strtolower($_POST['bebop_youtube_maximport']))));
   	update_site_option('bebop_youtube_setup', true);
      
   	foreach($arraySwitches as $switch){
       	update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
   	}  
   	echo '<div>Settings Saved</div>';
}
  //remove the user
if( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim($_GET['reset_user_id']);
	bebop_tables::remove_user_from_provider($user_id, 'youtube');
}
?>

<form method="post" action="">
	<table class="bebop_settings_table" cellspacing="0">          
	

			<th colspan='2'>Youtube settings</th>

<tr>
			<td>Max import:</td>
			<td><input type="text" name="bebop_youtube_maximport" value="<?php echo get_site_option('bebop_youtube_maximport'); ?>" size="5" /></td>
		</tr>
	
	</table>
	  <p class="submit"><input type="submit" class="button-primary" value="Save Settings"></p>
</form>

<table class='bebop_table'>
	<tr class='nodata'>
		<th>User ID</th>
		<th>Username</th>
		<th>User email</th>
		<th>Youtube Channel</th>
		<th>Options</th>
	</tr>
	<?php
	$user_metas = bebop_tables::get_user_ids_from_meta_name('bebop_youtube_username');	

	foreach( $user_metas as $user ) {
		
		$this_user = get_userdata($user->user_id);
		echo "<tr>
			<td>" . bebop_tables::sanitise_element($user->user_id) . "</td>
			<td>" . bebop_tables::sanitise_element($this_user->user_login) . "</td>
			<td>" . bebop_tables::sanitise_element($this_user->user_email) . "</td>
			<td>" . bebop_tables::sanitise_element(bebop_tables::get_user_meta_value( $user->user_id, 'bebop_youtube_username' ) ) . "</td>
			<td><a href='?page=bebop_youtube&reset_user_id=" . bebop_tables::sanitise_element($user->user_id) . "'>Reset User</a></td>
		</tr>";
	}
?>
	<!-- <End bebop_table -->
</table>