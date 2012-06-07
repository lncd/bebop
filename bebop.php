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
	
	//turn on debugging
	define('WP_DEBUG', true);
	
	//Define plugin version
	define('BP_BEBOP_VERSION', '0.1');
	//define('BP_BEBOP_IS_INSTALLED', 1);
	
	//init settings
	bebop_init_settings();
	
	//load languages
	bebop_init_languages();
	
	//include files from core.
	include_once( 'core/core.php' );
}

//Create the datases if they do not exist
function bebop_init_database( ) {
	if( ! get_site_option( "bebop_installed_version" ) ) {
		
		global $wpdb;
		
		//log table - to log errors
		$bebop_log_sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bebop_log (
			'id' int(10) NOT NULL auto_increment,
			'date' timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			'type' text NOT NULL,
			'message' text NOT NULL,
			PRIMARY KEY  ('id')
		);";
		
		//data table - to store data
		$bebop_data_sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bebop_data (
			'id' int(10) NOT NULL auto_increment,
			'option' text NOT NULL,
			'value' text NOT NULL,
			PRIMARY KEY  ('id')
		);";
		
		//run the queries
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $bebop_table_sql );
		dbDelta( $bebop_data_sql );
		
		//tests
		$sql_test = $wpdb->insert( $wpdb->prefix . "bebop_log", array( 'type' => 'test type', 'message' => 'test message' ) );
		$sql_test = $wpdb->insert( $wpdb->prefix . "bebop_data", array( 'option' => 'test option', 'value' => 'test value' ) );
		
		//save the installed version.
		$sql_test = $wpdb->insert( $wpdb->prefix . "bebop_data", array( 'option' => 'bebop_installed_version', 'value' => BP_BEBOP_VERSION ) );
		
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
register_activation_hook(__FILE__,'bebop_init_databases');
    
?>