<script type="text/javascript">
$be = jQuery.noConflict();
$be(document).ready( function() {
// Select all
	$be("A[href='#select_all']").click( function() {
		$be("INPUT[type='checkbox']", $be(this).attr('rel')).attr('checked', true);
		return false;
	});
	$be("A[href='#select_none']").click( function() {
		$be("INPUT[type='checkbox']", $be(this).attr('rel')).attr('checked', false);
		return false;
	});
});
</script>
<?php
if ( isset( $_POST['action'] ) ) {
	//Add OER's to the activity stream.
	if ( $_POST['action'] == 'verify' ) {
		foreach ( array_keys( $_POST ) as $oer ) {
			if ( $oer != 'action' ) {
				$data = bebop_tables::fetch_individual_oer_data( $oer ); //go and fetch data from the activity buffer table.
				if ( ! empty( $data->id ) ) {
					global $wpdb;
					
					//add it to the activity stream!
					//check if the secondary_id already exists
					$secondary_id = $wpdb->get_row( $wpdb->prepare( "SELECT secondary_item_id FROM {$bp->activity->table_name} WHERE secondary_item_id='" . $data->secondary_item_id . "'" ) );
			
					if ( empty( $secondary_id ) ) {
						$activity = new BP_Activity_Activity();
			
						add_filter( 'bp_activity_action_before_save', 'bp_activity_filter_kses', 1 );
						
						$activity->user_id				= $data->user_id;
						$activity->component			= 'bebop_oer_plugin';
						$activity->type					= $data->type;
						$activity->action				= $data->action;
						$activity->content				= $data->content;
						$activity->secondary_item_id	= $data->secondary_item_id;
						$activity->date_recorded	    = $data->date_recorded;
						
						if ( bebop_tables::get_option_value( 'bebop_'. $data->type . '_hide_sitewide' ) == 'on' ) {
							$activity->hide_sitewide = 1;
						}
						else {
							$activity->hide_sitewide = 0;
						}
						remove_filter( 'bp_activity_action_before_save', 'bp_activity_filter_kses', 1 );
						
						if ( $activity->save() ) {
							bebop_tables::update_oer_data( $data->secondary_item_id, 'status', 'verified' );
							bebop_tables::update_oer_data( $data->secondary_item_id, 'activity_stream_id', $activity_stream_id = $wpdb->insert_id );
							bebop_filters::day_increase( $data->type, $data->user_id );
						}
						else {
							bebop_tables::log_error( 'Activity Stream', 'Could not update the oer buffer status.' );
						}
					}
					else {
						bebop_tables::log_error( 'Activity Stream', 'This content already exists in the activity stream.' );
					}
				}
			}
		}//End foreach ( array_keys($_POST) as $oe ) {
	}//End if ( $_POST['action'] == 'verify' ) {
	else if ( $_POST['action'] == 'delete' ) {
		foreach ( array_keys( $_POST ) as $oer ) {
			if ( $oer != 'action' ) {
				$data = bebop_tables::fetch_individual_oer_data( $oer );//go and fetch data from the activity buffer table.
				if ( ! empty( $data->id ) ) {
					//delete the activity, let the filter update the tables.
					if ( ! empty( $data->activity_stream_id ) ) {
						bp_activity_delete(
										array(
											'id' => $data->activity_stream_id,
										)
						);
					}
					else {
						//else just update the status
						bebop_tables::update_oer_data( $data->secondary_item_id, 'status', 'deleted' );
					}
				}
			}
		} //End foreach ( array_keys( $_POST ) as $oer ) {
	}
	else if ( $_POST['action'] == 'reset' ) {
		foreach ( array_keys( $_POST ) as $oer ) {
			if ( $oer != 'action' ) {
				$data = bebop_tables::fetch_individual_oer_data( $oer );//go and fetch data from the activity buffer table.
				bebop_tables::update_oer_data( $data->secondary_item_id, 'status', 'unverified' );
			}
		}
	}
}

?>
<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<div class="button_container"><a class="standard_button min_width_100" href="?type=unverified">Unverified</a></div>
<div class="button_container"><a class="standard_button min_width_100" href="?type=verified">Verified</a></div>
<div class="button_container"><a class="standard_button min_width_100" href="?type=deleted">Deleted</a></div>
<?php
global $wpdb, $bp;
if ( isset( $_GET['type'] ) ) {
	$active_extensions = bebop_extensions::get_active_extension_names( $addslashes = true );
	$extension_names   = join( ',' ,$wpdb->escape( $active_extensions ) );
	if ( strtolower( strip_tags( $_GET['type'] == 'unverified' ) ) ) {
		$type = 'unverified';
	}
	else if ( strtolower( strip_tags( $_GET['type'] == 'verified' ) ) ) {
		$type = 'verified';
	}
	else if ( strtolower( strip_tags( $_GET['type'] == 'deleted' ) ) ) {
		$type = 'deleted';
	}
	if ( ! empty( $type ) ) {
		$oers = bebop_tables::fetch_oer_data( $bp->loggedin_user->id, $extension_names, $type );
		
		if ( count( $oers ) > 0 ) {
			echo '<form id="oer_table" class="bebop_user_form" method="post">';
			echo '<h4>' . ucfirst( $type ) . ' OERs</h4>
			<table class="bebop_user_table">
				<tr class="nodata">
					<th>Type</th>
					<th>Published</th>
					<th>Content</th>
					<th>Select</th>
				</tr>';
			
			foreach ( $oers as $oer ) {
				echo '<tr>
					<td><label for="' . $oer->secondary_item_id . '">' . bebop_tables::sanitise_element( ucfirst( $oer->type ) ) . '</label></td>' .
					'<td><label for="' . $oer->secondary_item_id . '">' . time_since( $oer->date_recorded ) . '</label></td>' .
					'<td class="content"><label for="' . $oer->secondary_item_id . '">' . bebop_tables::sanitise_element( $oer->content ) . '</label></td>' .
					"<td class='checkbox_container'><label for='" . $oer->secondary_item_id . "'><div class='checkbox'><input type='checkbox' id='" . $oer->secondary_item_id . "' name='" . $oer->secondary_item_id . "'></div></label></td>" .
				'</tr>';
			}
			echo '</table>';
			echo '<div class="button_container button_right"><a class="standard_button" rel="#oer_table" href="#select_all">Select All</a></div>';
			echo '<div class="button_container button_right"><a class="standard_button" rel="#oer_table" href="#select_none">Select None</a></div>';
			
			echo '<h4>Action</h4>';
			$verify_oer_option = '<label class="alt" for="verify">Verify:</label><input type="radio" name="action" id="verify" value="verify"><br>';
			$delete_oer_option = '<label class="alt" for="delete">Delete:</label><input type="radio" name="action" id="delete" value="delete"><br>';
			$reset_oer_option  = '<label class="alt" for="reset">Reset:</label><input type="radio" name="action" id="reset" value="reset"><br>';
			
			if ( $type == 'unverified' ) {
				echo $verify_oer_option . $delete_oer_option;
			}
			else if ( $type == 'verified' ) {
				echo $delete_oer_option;
			}
			else if ( $type == 'deleted' ) {
				echo $reset_oer_option;
			}
				
			echo '<div class="button_container"><input type="submit" class="standard_button clear_both" value="Submit"></div>
			</form>';
		}
		else {
			echo '<p>Unfortunately, we could not find any ' . $type . ' oers for you to manage.</p>';
		}
	}//End if ( ! empty( $type ) ) {
	else {
		echo '<p>Invalid OER type.</p>';
	}
	
}
?>