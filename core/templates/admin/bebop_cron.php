<?php 
//This handles the functionality of the crons with wordpress
class bebop_tables
{
	function myprefix_add_weekly_cron_schedule( $schedules ) {
    $schedules['weekly'] = array(
        'interval' => 10, // This will need changing to the database value stored. 'Its in seconds'
        'display'  => __( 'Once Weekly' ),
    );
 
    return $schedules;
	}
	
	function myprefix_function_to_run() {
    bebop_tables::log_general('cron', 'done log.');
	}
	
	function add_cron_action(){
		//Hook into that action that'll fire weekly.
		add_action( 'myprefix_my_cron_action', 'myprefix_function_to_run' );
	}
	
	function add_cron_filter(){
		//Adds the schedule interval time.
		add_filter( 'cron_schedules', 'myprefix_add_weekly_cron_schedule' );
	}
	
	function add_cron_schedule(){
		// Schedule an action if it's not already scheduled
		if ( ! wp_next_scheduled( 'myprefix_my_cron_action' ) ) {
    		wp_schedule_event( time(), 'weekly', 'myprefix_my_cron_action' );
		}
	}
	
}
?>