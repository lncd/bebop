<?php

if(isset($_GET['remove'])){
	//Removes the channel name.
     bebop_tables::remove_user_meta($bp->loggedin_user->id, 'bebop_youtube_username');
}


if ($_POST){
	//Updates the channel name.
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'bebop_youtube_username', $_POST['bebop_youtube_username']);
}

    $bebop_youtube_username = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'bebop_youtube_username');
    if ($bebop_youtube_username) {
      do_action('bebop_youtube_activated');
    }
?>
<form id="settings_form" action="<?php echo  $bp->loggedin_user->domain ?>bebop-oers/?oer=youtube" method="post">
    <h3>Youtube Settings</h3>
   	  Youtube username<br/>
  	  <input type="text" name="bebop_youtube_username" value="<?php echo $bebop_youtube_username; ?>" size="50" /><br/><br/>      
   	 <input type="submit" value="Save Channel">
</form>
<?php if($bebop_youtube_username != "") {
?>
<form id="settings_form" action="<?php echo  $bp->loggedin_user->domain; ?>bebop-oers/?oer=youtube" method="post">
    <input type="submit" name="remove" value="Remove Channel">
</form> 
<?php
}
?>