<?php
/*
 * Bebop Feeds
 */
add_action( 'bp_actions', 'bebop_feeds' );

function bebop_feeds() {
	global $bp, $wp_query, $this_bp_feed;
	if ( bp_is_activity_component() && bp_displayed_user_id() ) {
		$active_extensions = bebop_extensions::get_active_extension_names();
		$active_extensions[] = 'all_oers';
		foreach ( $active_extensions as $extension ) {
			if ( bp_current_action() == $extension ) {
				if ( ! defined( 'BEBOP_DISABLE_' . strtoupper($extension) . '_FEED' ) ) { //change this to extension setting in db, have option to enable/disable in admin options.
					$this_bp_feed = $extension;
				}
			}
		}
	}
	if ( empty( $this_bp_feed ) ) {
		return false;
	}
	//var_dump( $this_bp_feed );
	
	//bebop_feed_url();
	//bebop_get_feed_url();
	//bebop_activity_args();

	$wp_query->is_404 = false;
	status_header( 200 );

	include_once( 'bebop-feed-template.php' );
	die();
}

function bebop_feed_url() {
	echo bebop_get_feed_url();
}
function bebop_get_feed_url() {
	global $this_bp_feed;
	if ( ! empty( $this_bp_feed ) ) {
		return bp_displayed_user_domain() . bp_get_activity_slug() . '/' . $this_bp_feed . '/feed';
	}
	else {
		return false;
	}
}

function bebop_feed_name() {
	echo bebop_get_feed_name();
}
function bebop_get_feed_name() {
	global $this_bp_feed;
	if ( ! empty( $this_bp_feed ) ) {
		$feed = str_replace( '_', ' ', $this_bp_feed );
		return ucwords( $feed ) . ' Feed for ' . bp_get_displayed_user_fullname();
	}
	else {
		return false;
	}
}

function bebop_activity_args() {
	echo bebop_get_activity_args();
}
function bebop_get_activity_args() {
	global $this_bp_feed;
	if ( ! empty( $this_bp_feed ) ) {
		if( $this_bp_feed == 'all_oers' ) {
			return 'user_id=' . bp_displayed_user_id() . '&object=bebop_oer_plugin&max=50&display_comments=stream';
		}
		return 'user_id=' . bp_displayed_user_id() . '&object=bebop_oer_plugin&action=' . $this_bp_feed . '&max=50&display_comments=stream';
	}
	else {
		return false;
	}
}
?>