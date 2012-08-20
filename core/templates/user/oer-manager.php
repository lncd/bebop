<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<div class="button_container"><a class="standard_button min_width_100" href="?type=unverified">Unverified</a></div>
<div class="button_container"><a class="standard_button min_width_100" href="?type=verified">Verified</a></div>
<div class="button_container"><a class="standard_button min_width_100" href="?type=deleted">Deleted</a></div>
<?php
$type = bebop_get_oer_type();
if ( ! empty( $type ) ) {
	$oers = bebop_get_oers( $type );
	
	if ( count( $oers ) > 0 ) {
		echo '<form id="oer_table" class="bebop_user_form" method="post">';
		echo '<h4>' . ucfirst( $type ) . ' OERs</h4>
		<table class="bebop_user_table">
			<tr class="nodata">
				<th>Type</th>
				<th>Imported</th>
				<th>Published</th>
				<th>Content</th>
				<th>Select</th>
			</tr>';
		
		foreach ( $oers as $oer ) {
			echo '<tr>' .
				'<td><label for="' . $oer->secondary_item_id . '">' . bebop_tables::sanitise_element( $oer->type ) . '</label></td>' .
				'<td><label for="' . $oer->secondary_item_id . '">' . bebop_tables::sanitise_element( bp_core_time_since( $oer->date_imported ) ) . '</label></td>' .
				'<td><label for="' . $oer->secondary_item_id . '">' . bp_core_time_since( $oer->date_recorded ) . '</label></td>' .
				'<td class="content"><label for="' . $oer->secondary_item_id . '">' . bebop_tables::sanitise_element( $oer->content, $allow_tags = true ) . '</label></td>' .
				"<td class='checkbox_container'><label for='" . $oer->secondary_item_id . "'><div class='checkbox'><input type='checkbox' id='" . $oer->secondary_item_id . "' name='" . $oer->secondary_item_id ."'></div></label></td>" .
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
?>