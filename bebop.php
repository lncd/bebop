<?php
/*
Plugin Name: Bebop
Plugin URI: http://bebop.blogs.lincoln.ac.uk/
Description: Bebop is the name of a rapid innovation project funded by the Joint Information Systems Committee (JISC). The project involves the utilisation of OER's from 3rd party providers such as JORUM and slideshare.
Version: 0.1
Author: Dale Mckeown
Author URI: http://phone.online.lincoln.ac.uk/dmckeown
License: TBA
*/

// This plugin is intended for use on BuddyPress only.
// http://buddypress.org/

/*****************************************************************************
** This program is distributed WITHOUT ANY WARRANTY; without even the       **
** implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. **
*****************************************************************************/


//initialise Bebop
function bebop_init() {
	
	//Define plugin version
	define('BP_BEBOP_VERSION', '0.1');
	//define('BP_BEBOP_IS_INSTALLED', 1);
	
	//init database - used as a log
	bebop_init_database();
	
	//init settings
	bebop_init_settings();
	
	//load languages
	bebop_init_languages();
	
	//include files from core.
	include_once( 'core/core.php' );
}

//Create the log database if it does not already exist.
function bebop_init_database( ) {
	if( ! get_site_option( "bebop_installed_version" ) ) {
		global $wpdb;
		
		$bebop_table_sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bebop_log (
			'id' int(10) NOT NULL auto_increment,
			'date' timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			'type' text NOT NULL,
			'message' text NOT NULL,
			PRIMARY KEY  ('id')
		);";
		
		//run the query.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $bebop_table_sql );
		
		//test
		$sql_test = $wpdb->insert( $wpdb->prefix . "bebop_log", array( 'type' => 'test type', 'message' => 'test message' ) );
		
		//update the installed version.
		update_site_option("bebop_installed_version", BP_BEBOP_VERSION);
		
		//cleanup
		unset( $bebop_table_sql );
	}
}

function bebop_init_settings() {
	//not currently implemented
}

function bebop_init_languages() {
	//not currently implemented
}

//hook into bp_init to start bebop. 
add_action( 'bp_init', 'bebop_init', 4 );
    
?>