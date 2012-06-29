<?php


if(isset($_GET['remove'])){
	//Removes the channel name.
     bebop_tables::remove_user_meta($bp->loggedin_user->id, 'bs_youtube_username');
}


if ($_POST){
	//Updates the channel name.
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'bs_youtube_username', $_POST['bs_youtube_username']);
}

    $bs_youtube_username = bebop_tables::get_user_meta_value($bp->loggedin_user->id, 'bs_youtube_username');
    if ($bs_youtube_username) {
      do_action('buddystream_youtube_activated');
    }
?>
    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain.BP_XPROFILE_SLUG; ?>/bebop-oers/?oer=youtube" method="post">
        <h3>Youtube Settings</h3>
       	  Youtube username<br/>
      	  <input type="text" name="bs_youtube_username" value="<?php echo $bs_youtube_username; ?>" size="50" /><br/><br/>      
       	 <input type="submit" class="buddystream_save_button" value="Save Channel">
        </form>
    <?php if($bs_youtube_username != ""): ?>
    	<form id="settings_form" action="<?php echo  $bp->loggedin_user->domain.BP_XPROFILE_SLUG; ?>/bebop-oers/?oer=youtube" method="post">
            <input type="submit" name="remove" class="buddystream_save_button" value="Remove Channel">
        </form> 
    <?php endif; ?>
    