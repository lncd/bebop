<link rel="stylesheet" type="text/css" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css'; ?>">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<div id='bebop_admin_container'>
	
	<?php //echo BuddyStreamExtentions::tabLoader('twitter'); ?>
	
	<?php
	if ($_POST) {
	   bebop_tables::update_option('tweetstream_filter', trim(strip_tags(strtolower($_POST ['tweetstream_filter']))));
	   bebop_tables::update_option('tweetstream_filter_show', trim(strip_tags($_POST ['tweetstream_filter_show'])));
	   bebop_tables::update_option('tweetstream_filterexplicit', trim(strip_tags(strtolower($_POST ['tweetstream_filterexplicit']))));
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
                <td><input type="text" name="tweetstream_filter"value="<?php echo bebop_tables::get_option('tweetstream_filter');?>" size="50" /></td>
            </tr>

            <tr class="odd">
                <td>Explicit words</td>
                <td><input type="text" name="tweetstream_filterexplicit" value="<?php echo bebop_tables::get_option('tweetstream_filterexplicit');?>" size="50" /></td>
            </tr>
        </table>
        <p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
    </form>
</div>