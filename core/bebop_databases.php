<?php


class bebop_databases
{		
	//function to log errors into error table
	function log_error($feed_id=null, $error_type, $error_message) {
		global $wpdb;
		
		if($feed_id) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (%s, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
		else {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (NULL, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
	}
	
	//function to log errors into error table
	function log_general($type, $message) {
		global $wpdb;
		
		$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_general_log (type, message) VALUES (%s, %s)", $wpdb->escape($type), $wpdb->escape($message) ) );
	}
	
	function add_option($option, $value) {
		global $wpdb;
		
		if( get_option($option, $value) ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_general_log (option_name, option_value) VALUES (%s, %s)", $wpdb->escape($option), $wpdb->escape($value) ) );
			return true;
		}
		else {
			return "option already exists";
		}
	}
	function get_option($option, $value) {
		global $wpdb;
		return $wpdb->get_results( "SELECT option_value FROM " . $wpdb->base_prefix . "bp_bebop_options WHERE option_name = " );
	}
	function update_option($option, $value) {
		global $wpdb;
		
	}
	function remove_option($option, $value) {
		global $wpdb;
		
	}
}

$bebop_databases = new bebop_databases();
$bebop_databases->log_error('123', 'test error', 'test error message');
$bebop_databases->log_general('test log', 'test log message');

$bebop_databases->add_option('test_option', 'test_option');
?> 