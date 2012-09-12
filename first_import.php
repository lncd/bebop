<?php

/**
 * Importer for bebop
 */
set_time_limit( 60 );
ini_set( 'max_execution_time', 60 );

//load the WordPress loader
$current_path = getcwd();
$seeking_root  = pathinfo( $current_path );
$inc_path      = str_replace( 'wp-content/plugins','',$seeking_root['dirname'] );

ini_set( 'include_path', $inc_path );
include_once( 'wp-load.php' );

//include files from core.
include_once( 'core/bebop-data.php' );
include_once( 'core/bebop-oauth.php' );
include_once( 'core/bebop-tables.php' );
include_once( 'core/bebop-filters.php' );
include_once( 'core/bebop-pages.php' );
include_once( 'core/bebop-extensions.php' );

//Main content file
include_once( 'core/bebop-core.php' );


$importers = bebop_extensions::get_active_extension_names();

if ( ! empty( $importers ) ) {
	bebop_tables::log_general( 'First Importer', 'First importer service started.' ); 
	$return_array = array();
	foreach ( $importers as $extension ) {
		if ( bebop_tables::get_option_value( 'bebop_' . strtolower( $extension ) . '_provider' ) == 'on' ) {
			if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . strtolower( $extension ) . '/import.php' ) ) {
				include_once( WP_PLUGIN_DIR . '/bebop/extensions/' . strtolower( $extension ) . '/import.php' );
				if ( function_exists( 'bebop_' . strtolower( $extension ) . '_import' ) ) {
					$user_metas = bebop_tables::get_first_importers_by_extension( strtolower( $extension ) );
					if( count( $user_metas ) > 0 ) {
						$return_array[] = call_user_func( 'bebop_' . strtolower( $extension ) . '_import', strtolower( $extension ), $user_metas );
					}
				}
				else {
					bebop_tables::log_error( 'First Importer', 'The function: bebop_' . strtolower( $extension ) . '_import does not exist.' );
				}
			}
			else {
				bebop_tables::log_error( 'First Importer', 'The file: ' . WP_PLUGIN_DIR . '/bebop/extensions/' . strtolower( $extension ) . '/import.php does not exist.' );
			}
		}
	}
	$log_results = implode( ', ', $return_array );
	bebop_tables::log_general( 'First Importer', 'First importer service completed. Imported ' . $log_results . '.' );
}


/*bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_done_initial_import', 0 );
bebop_tables::add_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_' . $_POST['bebop_' . $extension['name'] . '_username'] . '_done_initial_import', 0 );

if( bebop_tables::get_user_meta_value( $specific_user, 'bebop_' . $this_extension['name'] . '_done_initial_import' ) == 0 ) {
		bebop_tables::update_user_meta( $specific_user, $this_extension['name'], 'bebop_' . $this_extension['name'] . '_done_initial_import', 1 );
		//declare as array so we do not have to modify the foreach statement.
		$user_metas = array();
		
		$user = new stdClass();
		$user->user_id = $specific_user;
		$user_metas[] = $user;
	}
else {
	bebop_tables::log_error( 'Importer - ' . ucfirst( $this_extension['name'] ), 'Someone tried to force an initial import for a user_id/feed name that has already done its first import. (possible hack attempt?). user_id:' .
	$specific_user );
}

if ( isset( $specific_user ) && isset( $specific_feed ) ) {
	$return_array[] = call_user_func( 'bebop_' . strtolower( $extension ) . '_import', strtolower( $extension ), $specific_user, $specific_feed );
}
else if ( isset( $specific_user ) ) {
	$return_array[] = call_user_func( 'bebop_' . strtolower( $extension ) . '_import', strtolower( $extension ), $specific_user );
}
*/
?>