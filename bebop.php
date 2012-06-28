<?php
//STOP CACHING THE PAGE!
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
/*
Plugin Name: Bebop
Plugin URI: http://bebop.blogs.lincoln.ac.uk/
Description: Bebop is the name of a rapid innovation project funded by the Joint Information Systems Committee (JISC). The project involves the utilisation of OER's from 3rd party providers such as JORUM and slideshare.
Version: 0.1
Author: Dale Mckeown
Author URI: http://phone.online.lincoln.ac.uk/dmckeown
License: TBA
Credits: BuddySteam - buddystrem.net
*/
// This plugin is intended for use on BuddyPress only.
// http://buddypress.org/

/*****************************************************************************
** This program is distributed WITHOUT ANY WARRANTY; without even the       **
** implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. **
*****************************************************************************/
	
//initialise Bebop
function bebop_init() {
		

	
	//init settings
	bebop_init_settings();

	//load languages
	bebop_init_languages();

	//include files from core.
	include_once( 'core/oauth.php' );
	include_once( 'core/bebop_tables.php' );
	include_once( 'core/bebop_filters.php' );
	include_once( 'core/bebop_page_loader.php' );
	include_once( 'core/bebop_extensions.php' );

	//Main content file
	include_once( 'core/bebop_core.php' );	
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
	    	error_type varchar(30) NOT NULL,
	    	error_message varchar(255) NOT NULL
	    );";
	    $bebop_general_log = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_general_log ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	    	type varchar(20) NOT NULL,
	    	message varchar(255) NOT NULL
	    );";
	
		$bebop_options = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_options ( 
	    	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	    	option_name varchar(30) NOT NULL PRIMARY KEY,
	    	option_value longtext NOT NULL
	    );";  
		
		$bebop_user_meta = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_user_meta ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	user_id int(10) NOT NULL,
	    	meta_name varchar(255) NOT NULL,
	    	meta_value longtext NOT NULL
	    );";   
		//run queries
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($bebop_error_log);
		dbDelta($bebop_general_log);   
		dbDelta($bebop_options);
		dbDelta($bebop_user_meta);
		
		//cleanup
		unset($bebop_error_log);
		unset($bebop_general_log);
		unset($bebop_options);
		unset($bebop_user_meta);
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

	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->base_prefix . "bp_bebop_general_log");
	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->base_prefix . "bp_bebop_error_log");
	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->base_prefix . "bp_bebop_options");
	$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->base_prefix . "bp_bebop_user_meta");
}

define('BP_BEBOP_VERSION', '0.1');


//hooks into activation and deactivation of the plugin.
register_activation_hook( __FILE__, 'bebop_activate' );
register_deactivation_hook( __FILE__, 'bebop_deactivate' );
//register_uninstall_hook( __FILE__, 'bebop_deactivate' )

add_action( 'bp_init', 'bebop_init', 5 );
