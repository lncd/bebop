<?php
bebop_extensions::load_extensions();

/*
 * Gets the url of a page
 */
function page_url( $last_folders = null ) {
	if ( isset( $_SERVER['HTTPS'] ) ) {
		if (  $_SERVER['HTTPS'] == 'on' ) {
			$page_url = 'https://';
		}
	}
	else {
		$page_url = 'http://';
	}
	if ( $_SERVER['SERVER_PORT'] != '80' ) {
		$page_url .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
	}
	else {
		$page_url .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}
	if ( $last_folders != null ) {
		$exp = array_reverse( explode( '/', $page_url ) );
		$arr = array();
		while ( $last_folders > 0 ) {
			$arr[] = $exp[$last_folders];
			$last_folders--;
		}
		$page_url = '/' . implode( '/', $arr ) . '/';
	}
	return $page_url;
}
/*
 * Adds an imported item to the buffer table
 */
function bebop_create_buffer_item( $params ) {
	global $bp, $wpdb;
	if ( is_array( $params ) ) {
		if ( ! bp_has_activities( 'secondary_id=' . $params['item_id'] ) ) {
			$originalText = $params['content'];
			$content = '';
			if ( $params['content_oembed'] == true ) {
				$content = $originalText;
			}
			else {
				$content = '<div class="bebop_activity_container ' . $params['extention'] . '">' . $originalText . '</div>';
			}
			if ( ! bebop_check_existing_content_buffer( $originalText ) ) {
				$action  = '<a href="' . bp_core_get_user_domain( $params['user_id'] ) .'" title="' . bp_core_get_username( $params['user_id'] ).'">'.bp_core_get_user_displayname( $params['user_id'] ) . '</a>';
				$action .= ' ' . __( 'posted&nbsp;a', 'bebop' . $extention['name'] ) . ' ';
				$action .= '<a href="' . $params['actionlink'] . '" target="_blank" rel="external"> '.__( $params['type'], 'bebop_'.$extention['name'] );
				$action .= '</a>: ';
				
				$oer_hide_sitewide = 0;
				$date_imported = gmdate( 'Y-m-d H:i:s', time() );
				
				//extra check to be sure we don't have a empty activity
				$clean_comment = '';
				$clean_comment = trim( strip_tags( $content ) );
				
				if ( ! empty( $clean_comment ) ) {
					if ( $wpdb->query(
									$wpdb->prepare(
													'INSERT INTO ' . bp_core_get_table_prefix() . 'bp_bebop_oer_manager ( user_id, status, type, action, content, secondary_item_id, date_imported, date_recorded, hide_sitewide ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s ) ',
													$wpdb->escape( $params['user_id'] ), 'unverified', $wpdb->escape( $params['extention'] ), $wpdb->escape( $action ), $wpdb->escape( $content ),
													$wpdb->escape( $params['item_id'] ), $wpdb->escape( $date_imported ), $wpdb->escape( $params['raw_date'] ), $wpdb->escape( $oer_hide_sitewide )
									)
					) ) {
						bebop_filters::day_increase( $params['extention'], $params['user_id'] );
					}
					else {
						bebop_tables::log_error( 'Importer', 'Import query error' );
					}
				}
				else {
					bebop_tables::log_error( 'Importer', 'Could not import, content already exists.' );
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			bebop_tables::log_error('activity ' . $params['item_id'] . ' already exists', serialize($secondary));
			return false;
		}
	}
	return true;
}


//hook and function to update status in the buffer table if the activity belongs to this plugin.
add_action( 'bp_activity_deleted_activities', 'update_bebop_status' );

function update_bebop_status( $deleted_ids ) {
	global $wpdb;
	foreach ( $deleted_ids as $id ) {
		$result = $wpdb->get_row( 'SELECT secondary_item_id FROM ' . bp_core_get_table_prefix() . "bp_bebop_oer_manager WHERE activity_stream_id = '" . $id . "'" );
		if ( ! empty( $result->secondary_item_id ) ) {
			bebop_tables::update_oer_data( $result->secondary_item_id, 'status', 'deleted' );
			bebop_tables::update_oer_data( $result->secondary_item_id, 'activity_stream_id', '' );
		}
	}
}

function bebop_check_existing_content_buffer( $content ) {
	global $wpdb, $bp;
	$content = strip_tags( $content );
	$content = trim( $content );
	
	if ( $wpdb->get_row( 'SELECT content FROM ' . bp_core_get_table_prefix() . "bp_bebop_oer_manager WHERE content LIKE '%" . $content . "%'" ) ) {
		return true;
	}
	else {
		return false;
	}
}

//Hook functions.

//This is a hook into the activity filter options.
add_action( 'bp_activity_filter_options', 'load_new_options' );

//This is a hook into the member activity filter options.
add_action( 'bp_member_activity_filter_options', 'load_new_options' );

//This function loads additional filter options for the extensions.
function load_new_options() {		
	$store = array();
	//gets only the active extension list.
	$active_extensions = bebop_extensions::get_active_extension_names();
	foreach ( $active_extensions as $extension ) {
		if ( bebop_tables::get_option_value( 'bebop_' . $extension . '_provider' ) == 'on' ) {
			$store[] = '<option value="' . ucfirst( $extension ) .'">' . ucfirst( $extension ) . '</option>';
		}
	}
	
	//Ensures the All OER only shows if there are two or more OER's to choose from.
	if ( count( $store ) > 1 ) {
		echo '<option value="all_oer">All OERs</option>';
	}
	
	//Outputs the options
	foreach ( $store as $option ) {
		echo $option;
	}
}

/*This function is added to the filter of the current query string and deals with the OER page
 * as well as the all_oer option. This is because the all_oer is not a type thus the query for what
 * to pull from the database needs to be created manually. */
function dropdown_query_checker( $query_string ) {
	global $bp;

	//Passes the query string as an array as its easy to determine the page number then "if any".
	parse_str( $query_string,$str );
	$page_number = '';
	//This checks if there is a certain page and if so ensure it is saved to be put into the query string.
	if ( isset( $str['page'] ) ) {
		$page_number = '&page=' . $str['page'];
	}
	
	//Checks if the all_oer has been selected or as a default on the bebop-oer page to show all_oer.
	if ( isset( $str['type'] ) ) {
		if ( $str['type'] === 'all_oer' || $bp->current_component === 'bebop-oers' && $str['type'] === NULL ) {
			//Sets the string_build variable ready.
			$string_build = '';
			
			//Loops through all the different extensions and adds the active extensions to the temp variable.
			$active_extensions = bebop_extensions::get_active_extension_names();
			foreach ( $active_extensions as $extension ) {
				if ( bebop_tables::get_option_value( 'bebop_' . $extension . '_provider' ) == 'on' ) {
					$string_build .= $extension . ',';
				}
			}
			
			/*Checks to make sure the string is not empty and if it is then simply returns all_oer which results in
			nothing being shown. */
			if ( $string_build !== '' ) {
				//Removes the end ","
				$string_build = substr( $string_build, 0, -1 );
				
				//Recreates the query string with the new views.
				$query_string = 'type=' . $string_build . '&action=' . $string_build;
				
				/*Puts the current page number onto the query for the all_oer.
				(others are dealt with by the activity stream processes)*/
				$query_string .= $page_number;
			}
		}
	}
	//Checks if its the OER page for the page limiter
	if ( $bp->current_component === 'bebop-oers' ) {
		//sets the reset session variable to allow for resetting activty stream if they have come from the oer page.
		$_SESSION['previous_area'] = 1;
		
		//Sets the page number for the bebop-oers page.
		$query_string .= '&per_page=10';
	}
	else {
		//This checks if the oer page was visited so it can reset the filters for the activity stream.
		if ( isset( $_SESSION['previous_area'] ) ) {
			session_unset( $_SESSION['previous_area'] );
			$query_string = ''; 
			/*
			 * This ensures that the default activity stream is reset if they have left the OER page.
			 * "This is done to stop the dropdown list and activity stream being the same as the oer 
			 * page was peviously on."
			 */
			echo  "<script type='text/javascript' src='" . WP_CONTENT_URL . '/plugins/bebop/core/resources/js/bebop_functions.js' . "'></script>";
			echo '<script type="text/javascript">';
			echo 'bebop_activity_cookie_modify("","");';
			echo '</script>';
			$_COOKIE['bp-activity-filter'] = '';
		}
	}
	//Returns the query string.
	return $query_string;
}
	
//This adds a hook before the loading of the activity data to adjust if all_oer is selected.
add_action( 'bp_before_activity_loop', 'load_new_options2' );

function load_new_options2() {
	//Adds the filter to the function to check for all_oer and rebuild the query if so.
	add_filter( 'bp_ajax_querystring', 'dropdown_query_checker' );
}