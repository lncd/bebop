<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css"'
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	
	<div class='postbox center_margin margin-bottom_22px'>
		<h3>OERs</h3>
		<div class="inside">
			Shows all of the OERs currently in the database, broken down by their status.
		</div>
	</div>
	<?php 
	if ( isset( $_GET['type'] ) ) {
		if ( strtolower( strip_tags( $_GET['type'] == 'unverified' ) ) ) {
			$type = 'unverified';
			$message = 'These OERs have not been approved to be displayed in owners activity streams.';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'verified' ) ) ) {
			$type = 'verified';
			$message = 'These OERs are currently being displayed their owner\'s activity streams.';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'deleted' ) ) ) {
			$type = 'deleted';
			$message = 'These OERs are not in the activity stream and have been marked as deleted by the owner.';
		}
	}
	else {
		$type = 'verified';
		$message = 'These OERs are currently being displayed their owner\'s activity streams.';
	}
	
	echo '<div class="button_container"><a class="options_button" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=unverified">Unverified OERs</a></div>';
	echo '<div class="button_container"><a class="options_button" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=verified">Verified OERs</a></div>';
	echo '<div class="button_container"><a class="options_button" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=deleted">Deleted OERs</a></div>';
	
	$oers = bebop_tables::admin_fetch_oer_data( $type );
	
	if ( count( $oers ) > 0 ) {
		echo '<h4>' . ucfirst( $type ) . ' OERs</h4>';
		echo $message;
		
		echo '<table class="bebop_table">
			<tr class="nodata">
				<th>Buffer ID</th>
				<th>Secondary ID</th>
				<th>Activity Stream ID</th>
				<th>Username</th>
				<th>OER Type</th>
				<th>Imported</th>
				<th>Published</th>
				<th>Content</th>
			</tr>';
		
		foreach ( $oers as $oer ) {
			echo '<tr>
				<td>' . $oer->id . '</td>' .
				'<td>' . $oer->secondary_item_id . '</td>' .
				'<td>' . $oer->activity_stream_id . '</td>' .
				'<td>' . bp_core_get_username( $oer->user_id ) . '</td>' .
				'<td>' . bebop_tables::sanitise_element( ucfirst( $oer->type ) ) . '</td>' .
				'<td>' . time_since( $oer->date_imported ) . '</td>' .
				'<td>' . time_since( $oer->date_recorded ) . '</td>' .
				'<td class="content">' . bebop_tables::sanitise_element( $oer->content ) . '</td>' .
			'</tr>';
		}
		echo '</table>';
	}
	else {
		echo '<h4>' . ucfirst( $type ) . ' OERs</h4>';
		echo '<p>No ' . $type . ' oers exist in the oer manager.</p>';
	}
		
	?>
	
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->