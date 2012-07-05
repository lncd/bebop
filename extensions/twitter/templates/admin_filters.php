<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css'; ?>">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<div id='bebop_admin_container'>
	
	<?php //echo BuddyStreamExtentions::tabLoader('twitter'); ?>
	
	<?php
	if ($_POST) {
	   bebop_tables::update_option('bebop_twitterfilter', trim(strip_tags(strtolower($_POST ['bebop_twitterfilter']))));
	   bebop_tables::update_option('bebop_twitterfilter_show', trim(strip_tags($_POST ['bebop_twitterfilter_show'])));
	   bebop_tables::update_option('bebop_twitterfilterexplicit', trim(strip_tags(strtolower($_POST ['bebop_twitterfilterexplicit']))));
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
                <td><input type="text" name="bebop_twitterfilter"value="<?php echo bebop_tables::get_option_value('bebop_twitterfilter');?>" size="50" /></td>
            </tr>

            <tr class="odd">
                <td>Explicit words</td>
                <td><input type="text" name="bebop_twitterfilterexplicit" value="<?php echo bebop_tables::get_option_value('bebop_twitterfilterexplicit');?>" size="50" /></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
    </form>
</div>