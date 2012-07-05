<?php 



add_filter( 'bp_get_activity_content','bebop_twitter_photos',5 );
add_filter( 'bp_get_activity_content_body','bebop_twitter_photos',5 );



function bebop_twitter_photos($text) {
 
    if( bp_get_activity_type() == 'twitter' ) {
        $text = preg_replace('#http://twitpic.com/([a-z0-9_]+)#i', '<a href="http://twitpic.com/\\1" target="_blank" rel="external"><img width="60" src="http://twitpic.com/show/mini/\\1" /></a>', $text);
        $text = preg_replace('#http://yfrog.com/([a-z0-9_]+)#i', '<a href="http://yfrog.com/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.com/\\1.th.jpg" /></a>', $text);
        $text = preg_replace('#http://yfrog.us/([a-z0-9_]+)#i', '<a href="http://yfrog.us/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.us/\\1:frame" /></a>', $text);
    }
    
  return $text;
}


/**
 * 
 * Page loader functions 
 *
 */

//called from  menu item creation on bebop_page_loader.php
function bebop_twitter() {
    bebop_extensions::page_loader('twitter');
}


function bebop_twitter_user_settings() {
	bebop_extensions::user_page_loader('twitter','settings');
}
