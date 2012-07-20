<?php
//STOP CACHING THE PAGE!
ob_start();
session_start();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
/*
Plugin Name: Bebop
Plugin URI: http://bebop.blogs.lincoln.ac.uk/
Description: Bebop is the name of a rapid innovation project funded by the Joint Information Systems Committee (JISC). The project involves the utilisation of OER's from 3rd party providers such as JORUM and slideshare.
Version: 0.1
Authors: Dale Mckeown, David Whitehead
Author URI: http://phone.online.lincoln.ac.uk/dmckeown, http://phone.online.lincoln.ac.uk/dwhitehead
License: TBA
Credits: BuddySteam - buddystream.net
*/
// This plugin is intended for use on BuddyPress only.
// http://buddypress.org/

/*****************************************************************************
** This software is released without warranty or guarantee, and as such no  **
*  liability or responsibility can be accepted by its authors or related     *
** organisations or institutions. YOU USE HIS SOFTWARE AT YOUR OWN RISK.    **
*****************************************************************************/

//initialise Bebop
function bebop_init() {
	
	//init settings
	bebop_init_settings();

	//load languages
	bebop_init_languages();

	//include files from core.
	include_once( 'core/bebop_oauth.php' );
	include_once( 'core/bebop_tables.php' );
	include_once( 'core/bebop_filters.php' );
	include_once( 'core/bebop_pages.php' );
	include_once( 'core/bebop_extensions.php' );

	//Main content file
	include_once( 'core/bebop_core.php' );	
	
	//fire cron
	add_action( 'bebop_cron', 'bebop_cron_function' ); 
	
	//Adds the schedule filter for changing the standard interval time.
	add_filter( 'cron_schedules', 'bebop_seconds_cron' );
	
	if ( ! wp_next_scheduled( 'bebop_cron' ) ) {
    	wp_schedule_event( time(), 'secs', 'bebop_cron' );
	}
}

function bebop_init_settings() {
	//not currently implemented
}
function bebop_init_languages() {
	//not currently implemented
}

//Code that should be fired when he plugin is activated.
function bebop_activate() {
	global $wpdb;
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if( is_plugin_active('buddypress/bp-loader.php') ) {
		//define table sql
		$bebop_error_log = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_error_log ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	feed_id int(10) NOT NULL,
	    	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	    	error_type varchar(40) NOT NULL,
	    	error_message varchar(255) NOT NULL
	    );";
	    $bebop_general_log = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_general_log ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	    	type varchar(40) NOT NULL,
	    	message varchar(255) NOT NULL
	    );";
	
		$bebop_options = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_options ( 
	    	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	    	option_name varchar(100) NOT NULL PRIMARY KEY,
	    	option_value longtext NOT NULL
	    );";  
		
		$bebop_user_meta = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_user_meta ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	user_id int(10) NOT NULL,
	    	meta_type varchar(255) NOT NULL,
	    	meta_name varchar(255) NOT NULL,
	    	meta_value longtext NOT NULL
	    );";   
	    
	    $bebop_activity_buffer = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_oer_buffer ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	user_id int(10) NOT NULL,
	    	status varchar(75) NOT NULL,
	    	type varchar(255) NOT NULL,
	    	action text NOT NULL,
	    	activity_stream_id int(20),
	    	content longtext NOT NULL,
	    	secondary_item_id varchar(75),
	    	date_recorded datetime,
	    	hide_sitewide tinyint(1)
	    );"; 
		//run queries
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($bebop_error_log);
		dbDelta($bebop_general_log);   
		dbDelta($bebop_options);
		dbDelta($bebop_user_meta);
		dbDelta($bebop_activity_buffer);
		
		//cleanup
		unset($bebop_error_log);
		unset($bebop_general_log);
		unset($bebop_options);
		unset($bebop_user_meta);
		unset($bebop_activity_buffer);
    }
	else {

		//BuddyPress is not installed, stop Bebop form activating and kill the script with an error message.
		include_once( 'core/bebop_tables.php' );
		bebop_tables::log_error(0, 'BuddyPress Error', 'BuddyPress is not active.');
		deactivate_plugins( basename(__FILE__) ); // Deactivate this plugin
		wp_die( "You cannot enable Bebop because BuddyPress is not active. Please install and activate BuddyPress before trying to activate Bebop again." );
	}
}
//remove the tables upon deactivation
function bebop_deactivate() {
	global $wpdb;
	
	//delete tables and clean up the activity data
	bebop_tables::drop_table('bp_bebop_general_log');
	bebop_tables::drop_table('bp_bebop_error_log');
	bebop_tables::drop_table('bp_bebop_options');
	bebop_tables::drop_table('bp_bebop_user_meta');
	bebop_tables::drop_table('bp_bebop_oer_buffer');
	bebop_tables::remove_activity_data();
	
	//delete the cron 
	wp_clear_scheduled_hook( 'bebop_cron' );	
}

//This function sets up the time interval for the cron schedule.
function bebop_seconds_cron( $schedules ) {
	
	//Gets the time or sets a default if they are new.
	if(bebop_tables::get_option_value('bebop_general_crontime'))
	{	
		$time = bebop_tables::get_option_value('bebop_general_crontime');
	}
	else
	{
		$time = 60;
		bebop_tables::update_option('bebop_general_crontime', $time);			
	} 
	
	$schedules['secs'] = array(
		'interval' =>$time,	//number of seconds
		'display'  => __( 'Once Weekly' )
	); 
	return $schedules;
}

function bebop_cron_function() {
	bebop_tables::log_general("bebop_cron", " Bebop cron import service started.");
	if(require_once( 'import.php' )){
		echo "loaded";
	}
	else {
		echo 'failed to load';
	}
	bebop_tables::log_general("bebop_cron", " Bebop cron import service completed.");
}

define('BP_BEBOP_VERSION', '0.1');


//hooks into activation and deactivation of the plugin.
register_activation_hook( __FILE__, 'bebop_activate' );
register_deactivation_hook( __FILE__, 'bebop_deactivate' );
//register_uninstall_hook( __FILE__, 'bebop_deactivate' );

add_action( 'bp_init', 'bebop_init', 5 );

ob_end_flush();
?>


