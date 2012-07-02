<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css'; ?>">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<div id='bebop_admin_container'>
	
	<?php //echo BuddyStreamExtentions::tabLoader('twitter'); ?>
	
	<?php
	if ($_POST) {
	   bebop_tables::update_option('twitter_filter', trim(strip_tags(strtolower($_POST ['twitter_filter']))));
	   bebop_tables::update_option('twitter_filter_show', trim(strip_tags($_POST ['twitter_filter_show'])));
	   bebop_tables::update_option('twitter_filterexplicit', trim(strip_tags(strtolower($_POST ['twitter_filterexplicit']))));
	   echo '<div>Saved</div>';
	}
	?>
	
    <div class="bebop_info_box">Filter Description</div>

    <form method="post" action="">
        <table class="buddystream_table" cellspacing="0">
           
            <tr class="header">
                <td colspan="2">filters(optional)</td>
            </tr>
            
            <tr>
                <td>Filters</td>
                <td><input type="text" name="twitter_filter"value="<?php echo bebop_tables::get_option_value('twitter_filter');?>" size="50" /></td>
            </tr>

            <tr class="odd">
                <td>Explicit words</td>
                <td><input type="text" name="twitter_filterexplicit" value="<?php echo bebop_tables::get_option_value('twitter_filterexplicit');?>" size="50" /></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
    </form>
</div>