<?php
//table manipulation.
class bebop_tables
{		
	function log_error( $feed_id=null, $error_type, $error_message ) { //function to log errors into the error table.
		global $wpdb;
		
		if( $feed_id ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (%s, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
		else {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (NULL, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
	}
	
	function log_general( $type, $message ) { //function to log general events into the log table.
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_general_log (type, message) VALUES (%s, %s)", $wpdb->escape($type), $wpdb->escape($message) ) );
	}
	
	function fetch_table_data($table_name) { //function to retrieve stuff from tables
		global $wpdb;
		
		$result = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . $table_name );
		return $result;
	}
	
	function add_option( $option_name, $option_value ) { //function to add option to the options table.
		global $wpdb;
		if( bebop_tables::get_option($option_name) == false ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_options (option_name, option_value) VALUES (%s, %s)", $wpdb->escape($option_name), $wpdb->escape($option_value) ) );
			return true;
			
		}
		else {
			bebop_tables::log_error( _, 'bebop_option_error', "option: '" . $option_name . "' already exists.");
			return false;
		}
	}
	
	function get_option( $option_name ) { //function to get an option from the options table.
		global $wpdb;
		$result = $wpdb->get_var( "SELECT option_value FROM " . $wpdb->base_prefix . "bp_bebop_options WHERE option_name = '" . $wpdb->escape($option_name) . "' LIMIT 1" );
		if( ! empty($result) ) {
			return $result;
			
		}
		else {
			return false;
		}
	}
	
	function update_option( $option_name, $option_value ) { //function to update an option in the options table.
		global $wpdb;
		
		if( bebop_tables::get_option($option_name) != false ) {
			$result = $wpdb->query( "UPDATE " . $wpdb->base_prefix . "bp_bebop_options SET option_value = '"  . $wpdb->escape($option_value) . "' WHERE option_name = '" . $wpdb->escape($option_name) . "' LIMIT 1" );
			if( ! empty($result) ) {
				return $result;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	
	function remove_option( $option_name) { //function to remove an option from the options table.
		global $wpdb;
		
		if( bebop_tables::get_option($option_name) != false ) {
			$wpdb->get_results( "DELETE FROM " . $wpdb->base_prefix . "bp_bebop_options  WHERE option_name = '" . $wpdb->escape($option_name) . "' LIMIT 1" );
			return true;
		}
		else{
			return false;
		}
	}
}
?> 