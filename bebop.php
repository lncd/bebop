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
	bebop_create_tables();
	
	//init settings
	bebop_init_settings();
	
	//load languages
	bebop_init_languages();
	
	//include files from core.
	include_once( 'core/bebop_tables.php' );
	include_once( 'core/bebop_filters.php' );
	include_once( 'core/bebop_page_loader.php' );

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
function bebop_create_tables() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if( is_plugin_active('buddypress/bp-loader.php') ) {
		//Database table stuffs
		global $wpdb;
		var_dump(is_plugin_active('buddypress/bp-loader.php'));
		
		//define table sql
		$bebop_error_log = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "bp_bebop_error_log ( 
	    	id int(10) NOT NULL auto_increment PRIMARY KEY,
	    	feed_id int(10) NULL,
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
	    	option_value varchar(30) NOT NULL
	    );";       
		//run queries
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($bebop_general_log);
		dbDelta($bebop_error_log);   
		dbDelta($bebop_options);
	
		//cleanup
		unset($bebop_general_log);
	    unset($bebop_error_log);
	    unset($bebop_options);
	}
	else {
		add_action('admin_notices', 'my_admin_notice');
	}
}
	    
function my_admin_notice(){
	echo '<div class="updated">
	<p>This notice only appears on the plugins page.</p>
	</div>';
}
//definitions

//define('WP_DEBUG', true);
define('BP_BEBOP_VERSION', '0.1');
define('BP_BEBOP_IS_INSTALLED', 1);
define( 'BP_USE_WP_ADMIN_BAR', true );

//hook into bp_init to start bebop. 
add_action( 'bp_init', 'bebop_init', 4 );

//register_uninstall_hook( __FILE__, 'bebop_deactivate' )

?>