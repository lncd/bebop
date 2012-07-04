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
?>

<div class="bebop_info_box">Youtube settings</div>

<form method="post" action="">
	<table class="bebop_settings_table" cellspacing="0">          
	
		<tr class="header">
			<td colspan="2">Header</td>
		</tr>
		
		<tr>
			<td>Album</td>
			<td><input class="switch icons" type="checkbox" name="bebop_youtube_album" id="buddystream_youtube_album"/></td>
		</tr>
		
		<tr class="odd">
			<td>Hide sitewide?</td>
			<td><input class="switch icons" type="checkbox" name="bebop_youtube_hide_sitewide" id="bebop_youtube_hide_sitewide"/></td>
		</tr>
		
		<tr>
			<td>Max import:</td>
			<td><input type="text" name="bebop_youtube_maximport" value="<?php echo get_site_option('bebop_youtube_maximport'); ?>" size="5" /></td>
		</tr>
	
	</table>
	  <p class="submit"><input type="submit" class="button-primary" value="Save Settings"></p>
</form>