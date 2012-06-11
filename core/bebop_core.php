<?php


class bebop
{
	//function to log errors into error table
	function log_error($feed_id=null, $error_type, $error_message) {
		if($feed_id) {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (%s, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
		else {
			$wpdb->query( $wpdb->prepare( "INSERT INTO " . $wpdb->base_prefix . "bp_bebop_error_log (feed_id, error_type, error_message) VALUES (NULL, %s, %s)", $wpdb->escape($feed_id), $wpdb->escape($error_type), $wpdb->escape($error_message) ) );
		}
		
	}
}

$bebop = new bebop();
//$bebop->log_error('123', 'test error', 'test error message');
?>