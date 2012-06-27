<?php
//table manipulation.
class bebop_tables
{
	/*
	 * Tables
	 */	
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
	
	/*
	 * Options
	 */
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
		$result = $wpdb->get_row( "SELECT option_value, option_name FROM " . $wpdb->base_prefix . "bp_bebop_options WHERE option_name = '" . $wpdb->escape($option_name) . "' LIMIT 1" );
		if( ! empty($result->option_value) ) {
			return $result->option_value;
		}
		else if ( ! empty($result->option_name) ) {
			return $result->option_name;
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
			bebop_tables::add_option( $option_name, $option_value );
			bebop_tables::update_option( $option_name, $option_value );
		}
	}
	
	function remove_option( $option_name ) { //function to remove an option from the options table.
		global $wpdb;
		
		if( bebop_tables::get_option($option_name) != false ) {
			$wpdb->get_results( "DELETE FROM " . $wpdb->base_prefix . "bp_bebop_options  WHERE option_name = '" . $wpdb->escape($option_name) . "' LIMIT 1" );
			return true;
		}
		else{
			return false;
		}
	}
	
	/*
	 * User Meta
	 */
	function add_user_meta( $user_id, $meta_name, $meta_value ) { //function to add user meta to the user_meta table.
		global $wpdb;
		
		if( bebop_tables::check_user_meta_exists($user_id, $meta_name) == false ) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_user_meta (user_id, meta_name, meta_value) VALUES (%s, %s, %s)", $wpdb->escape($user_id), $wpdb->escape($meta_name), $wpdb->escape($meta_value) ) );
			return true;
			
		}
		else {
			bebop_tables::log_error( _, 'bebop_user_meta_error', "meta: '" . $meta_name . "' already exists for user " . $user_id);
			return false;
		}
	}
	
	function check_user_meta_exists( $user_id, $meta_name ) { //function to check if user meta name exists in the user_meta table.
		global $wpdb;
		$result = $wpdb->get_row( "SELECT meta_name FROM " . $wpdb->base_prefix . "bp_bebop_user_meta WHERE user_id = '" . $wpdb->escape($user_id) . "' AND meta_name = '" . $wpdb->escape($meta_name) . "' LIMIT 1" );
		
		if ( ! empty($result->meta_name) ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function get_user_meta_value( $user_id, $meta_name ) { //function to get user meta from the user_meta table.
		global $wpdb;
		$result = $wpdb->get_row( "SELECT meta_value FROM " . $wpdb->base_prefix . "bp_bebop_user_meta WHERE user_id = '" . $wpdb->escape($user_id) . "' AND meta_name = '" . $wpdb->escape($meta_name) . "' LIMIT 1" );
		if( ! empty($result->meta_value) ) {
			return $result->meta_value;
		}
		else {
			return false;
		}
	}
	
	function update_user_meta( $user_id, $meta_name, $meta_value ) { //function to update user meta in the user_meta table.
		global $wpdb;
		
		if( bebop_tables::check_user_meta_exists($user_id, $meta_name) == true ) {
			$result = $wpdb->query( "UPDATE " . $wpdb->base_prefix . "bp_bebop_user_meta SET meta_value = '"  . $wpdb->escape($meta_value) . "' WHERE user_id = '" . $wpdb->escape($user_id) . "' AND meta_name = '" . $wpdb->escape($meta_name) . "' LIMIT 1" );
			if( ! empty($result) ) {
				return $result;
			}
			else {
				return false;
			}
		}
		else {
			bebop_tables::add_user_meta( $user_id, $meta_name, $meta_value );
			bebop_tables::update_user_meta( $user_id, $meta_name, $meta_value );
		}
	}
	
	function remove_user_meta( $user_id, $meta_name ) { //function to remove user meta from the user_meta table.
		global $wpdb;
		
		if( bebop_tables::check_user_meta_exists($user_id, $meta_name) == true ) {
			$wpdb->get_results( "DELETE FROM " . $wpdb->base_prefix . "bp_bebop_user_meta  WHERE user_id = '" . $wpdb->escape($user_id) . "' AND meta_name = '" . $wpdb->escape($meta_name) . "' LIMIT 1" );
			return true;
		}
		else{
			return false;
		}
	}
	
	//maybe use this when tables can be considered stable. instead of inline escapes.
	function sanitise_element($data) {
		return $wpdb->escape(stripslashes(strip_tags($data)));
	}
} 