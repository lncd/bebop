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

	//define('WP_DEBUG', true);
	
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

//Code that should be fired when he plugin is activated.
function bebop_activate() {
	
//Database table stuffs
global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $buddystreamSql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_log (
          'id' int(11) NOT NULL auto_increment,
          'date' timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
          'type' text NOT NULL,
          'message' text NOT NULL,
          PRIMARY KEY  ('id')
        );";

        dbDelta($buddystreamSql);
        unset($buddystreamSql);
}

function bebop_init_settings() {
	//not currently implemented
}
function bebop_init_languages() {
	//not currently implemented
}
//remove the tables upon deactivation
/*function bebop_deactivate() {
	global $wpdb;
	
	$bebop_log_sql = $wpdb->query("DROP TABLE IF EXISTS bebop_log");
	$bebop_data_sql = $wpdb->query("DROP TABLE IF EXISTS bebop_date");
}*/

//hook into bp_init to start bebop. 
add_action( 'bp_init', 'bebop_init', 4 );

//init tables when the plugin is activated.
register_activation_hook( __FILE__, 'bebop_activate' );

//register_deactivation_hook( __FILE__, 'bebop_deactivate' );
    
?>