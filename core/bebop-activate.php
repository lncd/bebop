<?php
global $wpdb;

//create tables if necessary.
$bebop_error_log = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_error_log ( 
	id bigint(20) NOT NULL auto_increment PRIMARY KEY, 
	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	error_type varchar(40) NOT NULL,
	error_message text NOT NULL
);';
$bebop_general_log = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_general_log ( 
	id bigint(20) NOT NULL auto_increment PRIMARY KEY,
	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	type varchar(40) NOT NULL,
	message text NOT NULL
);';

$bebop_options = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_options ( 
	timestamp timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,	
	option_name varchar(100) NOT NULL PRIMARY KEY,
	option_value longtext NOT NULL
);';

$bebop_user_meta = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_user_meta ( 
	id bigint(20) NOT NULL auto_increment PRIMARY KEY,
	user_id bigint(20) NOT NULL,
	meta_type varchar(255) NOT NULL,
	meta_name varchar(255) NOT NULL,
	meta_value longtext NOT NULL
);';

$bebop_oer_manager = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager ( 
	id bigint(20) NOT NULL auto_increment PRIMARY KEY,
	user_id bigint(20) NOT NULL,
	status varchar(75) NOT NULL,
	type varchar(255) NOT NULL,
	action text NOT NULL,
	content longtext NOT NULL,
	activity_stream_id bigint(20),
	secondary_item_id varchar(20),
	date_imported datetime,
	date_recorded datetime,
	hide_sitewide tinyint(1)
);';

$bebop_first_import = 'CREATE TABLE IF NOT EXISTS ' . bp_core_get_table_prefix() . 'bp_bebop_first_imports ( 
	id bigint(20) NOT NULL auto_increment PRIMARY KEY,
	user_id bigint(20) NOT NULL,
	extension varchar(255) NOT NULL,
	name varchar(255) NOT NULL,
	value longtext NOT NULL
);'; 
//run queries
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $bebop_error_log );
dbDelta( $bebop_general_log );
dbDelta( $bebop_options );
dbDelta( $bebop_user_meta );
dbDelta( $bebop_oer_manager );
dbDelta( $bebop_first_import );

//cleanup
unset( $bebop_error_log );
unset( $bebop_general_log );
unset( $bebop_options );
unset( $bebop_user_meta );
unset( $bebop_oer_manager );
unset( $bebop_first_import );


//Scripts to change DB architecture and data from from previous versions of bebop to the latest version.


//1 - update wp_bp_activity to use wp_bp_bebop_oer_manager id as the wp_bp_activity primary_item_id and remove secondary item_id
$update_1 = bebop_tables::get_option_value( 'bebop_db_update_1' );
if ( ! $update_1 ) {
	$results = $wpdb->get_results( 'SELECT secondary_item_id FROM ' . bp_core_get_table_prefix() . 'bp_activity WHERE component = "bebop_oer_plugin"' );
	foreach ( $results as $result ) {
		//get it's wp_bp_bebop_oer_manager id
		$id = $wpdb->get_row( 'SELECT id FROM ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager WHERE secondary_item_id = "' . $wpdb->escape( $result->secondary_item_id ) . '"' );
		//remove secondary_item_id, add item_id
		$update = $wpdb->get_row( 'UPDATE ' . bp_core_get_table_prefix() . 'bp_activity SET item_id = "' . $wpdb->escape( $id->id ) . '", secondary_item_id = "" WHERE secondary_item_id = "' . $wpdb->escape( $result->secondary_item_id ) . '"' );
	}
	bebop_tables::add_option( 'bebop_db_update_1', true );
}

//2 - update column definitions
$update_2 = bebop_tables::get_option_value( 'bebop_db_update_2' );
if ( ! $update_2 ) {
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager MODIFY secondary_item_id VARCHAR(20)' );
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager MODIFY id BIGINT(20)' );
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager MODIFY user_id BIGINT(20)' );
	
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_user_meta MODIFY id BIGINT(20)' );
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_user_meta MODIFY user_id BIGINT(20)' );
	
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_first_imports MODIFY id BIGINT(20)' );
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_first_imports MODIFY user_id BIGINT(20)' );
	
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_error_log MODIFY id BIGINT(20)' );
	$wpdb->get_results( 'ALTER TABLE ' . bp_core_get_table_prefix() . 'bp_bebop_general_log MODIFY id BIGINT(20)' );
	//bebop_tables::add_option( 'bebop_db_update_2', true );
}






?>