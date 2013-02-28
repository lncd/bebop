<?php

function print_args( $args )
{
	echo '<pre>';
	var_dump( $args );
	echo '</pre>';
}

//Scripts to change DB architecture and data from from previous versions of bebop to the latest version.
global $wpdb;

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