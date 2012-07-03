<?php

/**
 * Replace all embed urls into new embed urls (old content)
 */

add_filter( 'bp_get_activity_content','bebop_youtube_embed', 8);
add_filter( 'bp_get_activity_content_body','bebop_youtube_embed', 8);

function bebop_youtube_embed($text) {
    $return = "";
    $return = $text;
    $return = str_replace('watch/?v=', 'embed/', $return);
    return $return; 
}

function bebop_youtube()
{
  bebop_extensions::page_loader('youtube');
}