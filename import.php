<?php
/**
 * Importer for bebop
 */
 bebop_tables::log_general( 'Importer', 'Importer service started.' );
set_time_limit( 1000 );
ini_set( 'max_execution_time', 1000 );
include( ABSPATH . 'wp-load.php' );

//if import a specific OER.
if ( isset( $_GET['provider'] ) ) {
	$importers[] = $_GET['provider'];
}
else {
	$active_extensions = bebop_extensions::get_active_extension_names();
	//save importers to database
	bebop_tables::update_option( 'bebop_importers', implode( ',', $active_extensions ) );
	
	//check if there is a import queue, if empty reset
	if ( ! bebop_tables::get_option_value( 'bebop_importers_queue' ) ) {
		bebop_tables::update_option( 'bebop_importers_queue', implode( ',', $active_extensions ) );
	}
	$importers = bebop_tables::get_option_value( 'bebop_importers_queue' );
	$importers = explode( ',', $importers );
}

//start the importer for real
$return_array = array();
foreach ( $importers as $importer ) {
	if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $importer . '/import.php' ) ) {
		if ( bebop_tables::get_option_value( 'bebop_' . $importer . '_provider' ) == 'on' ) {
			include_once( WP_PLUGIN_DIR . '/bebop/extensions/' . $importer . '/import.php' );
			if ( function_exists( 'bebop_' . strtolower( $importer ) . '_start_import' ) ) {
				$return_array[] = call_user_func( 'bebop_' . strtolower( $importer ) . '_start_import' );
			}
		}
	}
}

$log_results = implode( ', ', $return_array );
bebop_tables::log_general( 'Importer', 'Importer service completed. Imported ' . $log_results . '.' );