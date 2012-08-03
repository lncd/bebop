<?php 
/*
 * Use this page to store and additional functions or filters which you may require for your plugin to work as expected.
 * For example, the code below adds oembed support for slideshare to the main bp activity feed and the custom Open Educational Resources tab.
 */

// Add Slideshare oEmbed
function add_oembed_slideshare() {
	wp_oembed_add_provider( 'http://www.slideshare.net/*', 'http://www.slideshare.net/api/oembed/2' );
}
//hook into bp_init as well as our custom oer tab.
add_action( 'bp_init','add_oembed_slideshare' );
add_action( 'bp_ajax_querystring','add_oembed_slideshare' );
?>