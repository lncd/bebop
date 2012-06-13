<?php


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
	
	function add_option( $option, $value ) { //function to add option to the options table.
		global $wpdb;
		if( $this->get_option($option) == false ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_options (option_name, option_value) VALUES (%s, %s)", $wpdb->escape($option), $wpdb->escape($value) ) );
			return true;
			
		}
		else {
			$this->log_error( _, 'bebop_option_error', "option '" . $option . "' already exists.");
			return false;
		}
	}
	
	function get_option( $option ) { //function to get an option from the options table.
		global $wpdb;
		$result = $wpdb->get_results( "SELECT option_value FROM " . $wpdb->base_prefix . "bp_bebop_options WHERE option_name = '" . $wpdb->escape($option) . "' LIMIT 1" );
		if( ! empty($result[0]->option_value) ) {
			return $result[0]->option_value;
			
		}
		else {
			return false;
		}
	}
	
	function update_option( $option, $value ) { //function to update an option in the options table.
		global $wpdb;
		
	}
	
	function remove_option( $option, $value ) { //function to remove an option from the options table.
		global $wpdb;
		
	}
}
$bebop_tables = new bebop_tables();

//Tests 
$bebop_tables->log_error(123, 'test error', 'test error message');
$bebop_tables->log_general('test log', 'test log message');

$bebop_tables->add_option('test_option', 'test_option');
//$bebop_tables->add_option('test_option', 'test_option');
//$bebop_tables->add_option('test_option', 'test_option');

$installed_version = $bebop_tables->get_option('bebop_installed_version');
?> 