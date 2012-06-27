<?php


if(isset($_GET['reset'])){
     bebop_tables::remove_user_meta($bp->loggedin_user->id, 'bs_youtube_username');
}

if ($_POST) {
    bebop_tables::update_user_meta($bp->loggedin_user->id, 'bs_youtube_username', $_POST['bs_youtube_username']);
    echo '<div class="buddystream_message">
            ' . __('Settings saved', 'buddystream_lang') . '
        </div>';
    }

    $bs_youtube_username = bebop_tables::get_user_meta($bp->loggedin_user->id, 'bs_youtube_username',1);
    if ($bs_youtube_username) {
      do_action('buddystream_youtube_activated');
    }
?>

    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain.BP_XPROFILE_SLUG; ?>/bebop-oers/?oer=youtube" method="post">
        <h3>Youtube Settings</h3>
        Youtube username<br/>
        <input type="text" name="bs_youtube_username" value="<?php echo $bs_youtube_username; ?>" size="50" /><br/><br/>
      
        <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">
        
        <?php if($bs_youtube_username != ""): ?>
            <a href="?network=youtube&reset=true" class="buddystream_reset_button"><?php echo __('Remove Youtube synchronization.','buddystream_facebook');?></a> 
        <?php endif; ?>
    </form>