<?php
/**
 * Importer for bebop
 */
set_time_limit( 900 );
ini_set( 'max_execution_time', 900 );
include( ABSPATH . 'wp-load.php' );

//if import a specific OER.
if( isset($_GET['oer']) ) {
	$importers[] = $_GET['oer'];
}

if( ! isset($_GET['oer']) ) {
	$handle     = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
	$extensions = array();
    //loop extentions so we can add active extentions to the import loop
    if ( $handle ) {
    	while ( false != ( $file = readdir( $handle ) ) ) {
    		if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
    			if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/import.php' ) ) {
    				if ( bebop_tables::get_option_value( 'bebop_' . $file . '_provider' ) == 'on' ) {
    					$extensions[] = $file;
					}
				}
			}
		}
	}
	
	//save importers to database
	bebop_tables::update_option( 'bebop_importers', implode( ',', $extensions ) );
	
	//check if there is a import queue, if empty reset
	if ( ! bebop_tables::get_option_value( 'bebop_importers_queue' ) ) {
		bebop_tables::update_option( 'bebop_importers_queue', implode( ',', $extensions ) );
	}
	$importers = bebop_tables::get_option_value( 'bebop_importers_queue' );
	$importers = explode( ',', $importers );
}

//start the importer for real
foreach($importers as $importer) {
	if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $importer . '/import.php' ) ) {
		if ( bebop_tables::get_option_value( 'bebop_' . $importer . '_provider' ) ) {
			include_once( WP_PLUGIN_DIR . '/bebop/extensions/' . $importer . '/import.php' );
			if ( function_exists( 'bebop_' . strtolower( $importer ) . '_start_import' ) ) {
				$numberOfItems = call_user_func( 'bebop_' . strtolower( $importer ) . '_start_import' );
			}
		}
	}
}