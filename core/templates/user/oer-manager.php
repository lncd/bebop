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
							bebop_tables::log_error( '_', 'Activity Stream', 'Could not update the oer buffer status.' );
						}
					}
					else {
						bebop_tables::log_error( '_', 'Activity Stream', 'This content already exists in the activity stream.' );
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
}

?>
<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<?php
global $wpdb, $bp;

$active_extensions = bebop_extensions::get_active_extension_names( $addslashes = true );
$extension_names   = join( ',' ,$wpdb->escape( $active_extensions ) );

$unverified_oers	= bebop_tables::fetch_oer_data( $bp->loggedin_user->id, $extension_names, 'unverified' );
$verified_oers  	= bebop_tables::fetch_oer_data( $bp->loggedin_user->id, $extension_names, 'verified' );
$removed_oers  		= bebop_tables::fetch_oer_data( $bp->loggedin_user->id, $extension_names, 'deleted' );

if ( ( count( $unverified_oers ) > 0 ) || ( count( $verified_oers ) > 0 ) || ( count( $removed_oers ) > 0 ) ) {
	if ( count( $unverified_oers ) > 0 ) {
		echo '<form class="bebop_user_form" method="post">';
		echo '<h4> Unverified OERs</h4>
		<table class="bebop_user_table width_90">
			<tr>
				<th>Type</th>
				<th>Published</th>
				<th>Content</th>
				<th>Select</th>
			</tr>';
			
		foreach ( $unverified_oers as $unverified_oer ) {
			echo '<tr>
				<td>' . bebop_tables::sanitise_element( ucfirst( $unverified_oer->type ) ) . '</td>' .
				'<td>' . time_since( $unverified_oer->date_recorded ) . '</td>' .
				'<td>' . bebop_tables::sanitise_element( $unverified_oer->content ) . '</td>' .
				"<td class='checkbox_container'><div class='checkbox'><input type='checkbox' id='" . $unverified_oer->secondary_item_id . "' name='" . $unverified_oer->secondary_item_id . "'></div></td>" .
			'</tr>';
		}
		echo '</table>';
		echo "
			<h4>Action</h4>
			<label for='verify'>Verify:</label><input type='radio' name='action' id='verify' value='verify'><br>
			<label for='delete'>Delete:</label><input type='radio' name='action' id='delete' value='delete'><br>
			
			<input type='submit' class='button_auth' value='Submit'>
		</form>";
	}
	if ( count( $verified_oers ) > 0 ) {
		echo '<form class="bebop_user_form" method="post">';
		echo '<h4> Verified OERs</h4>
		<table class="bebop_user_table width_90">
			<tr>
				<th>Type</th>
				<th>Published</th>
				<th>Content</th>
				<th>Select</th>
			</tr>';
			
		foreach ( $verified_oers as $verified_oer ) {
			echo '<tr>
				<td>' . bebop_tables::sanitise_element( ucfirst( $verified_oer->type ) ) . '</td>' .
				'<td>' . time_since( $verified_oer->date_recorded ) . '</td>' .
				'<td>' . bebop_tables::sanitise_element( $verified_oer->content ) . '</td>' .
				"<td class='checkbox_container'><div class='checkbox'><label for='" . $verified_oer->secondary_item_id . "'></label><input type='checkbox' id='" . $verified_oer->secondary_item_id . "' name='" . $verified_oer->secondary_item_id . "'></div></td>" .
			'</tr>';
		}
		echo '</table>';
		echo '
			<h4>Action</h4>
			<label for="delete">Delete:</label><input type="radio" name="action" id="delete" value="delete"><br>
			
			<input type="submit" class="button_auth" value="Submit">
		</form>';
	}
	if ( count( $removed_oers ) > 0 ) {
		echo '<form class="bebop_user_form" method="post">';
		echo 
		'<h4> Removed OERs</h4>
		<table class="bebop_user_table width_90">
			<tr>
				<th>Type</th>
				<th>Published</th>
				<th>Content</th>
				<th>Select</th>
			</tr>';
			
		foreach ( $removed_oers as $removed_oer ) {
			echo '<tr>
				<td>' . bebop_tables::sanitise_element( ucfirst( $removed_oer->type ) ) . '</td>' .
				'<td>' . time_since( $removed_oer->date_recorded ) . '</td>' .
				'<td>' . bebop_tables::sanitise_element( $removed_oer->content ) . '</td>' .
				"<td class='checkbox_container'><div class='checkbox'><label for='" . $removed_oer->secondary_item_id . "'></label><input type='checkbox' id='" . $removed_oer->secondary_item_id . "' name='" . $removed_oer->secondary_item_id . "'></div></td>" .
			'</tr>';
		}
		echo '</table>';
		echo '
			<h4>Action</h4>
			<label for="delete">Delete:</label><input type="radio" name="action" id="delete" value="delete"><br>
			<input type="submit" class="button_auth" value="Submit">
		</form>';
	}
}
else {
	echo '<p>Unfortunately, we could not find any OERs to manage.</p>';
}
?>