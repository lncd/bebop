<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	
	<div class='postbox center_margin margin-bottom_22px'>
		<h3><?php _e( 'OERs', 'bebop' ); ?></h3>
		<div class="inside">
			<p><?php _e( 'Lists all the OERs in the database by type.', 'bebop' ); ?></p>
		</div>
	</div>
	<?php 
	if ( isset( $_GET['type'] ) ) {
		if ( strtolower( strip_tags( $_GET['type'] == 'unverified' ) ) ) {
			$type = 'unverified';
			$message = __( 'These OERs have not been approved to be displayed in owners activity streams.', 'bebop' );
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'verified' ) ) ) {
			$type = 'verified';
			$message = __( 'These OERs are currently being displayed in their owner\'s activity streams.', 'bebop' );
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'deleted' ) ) ) {
			$type = 'deleted';
			$message = __( 'These OERs are not in the activity stream and have been marked as deleted by the owner.', 'bebop' );
		}
	}
	else {
		$type = 'verified';
		$message = __( 'These OERs are currently being displayed their owner\'s activity streams.', 'bebop' );
	}
	echo '<a class="button-secondary" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=unverified">'; _e( 'Unverified OERs', 'bebop' ); echo '</a>';
	echo '<a class="button-secondary" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=verified">'; _e( 'Verified OERs', 'bebop' ); echo '</a>';
	echo '<a class="button-secondary" href="' . $_SERVER['PHP_SELF'] . '?page=bebop_oers&type=deleted">'; _e( 'Deleted OERs', 'bebop' ); echo '</a>';
	
	$oers = bebop_tables::admin_fetch_oer_data( $type );
	
	if ( count( $oers ) > 0 ) {
		echo '<h4>' . ucfirst( $type ) . ' '; _e( 'OERs', 'bebop' ); echo '</h4>';
		echo $message;
		
		
		echo '<table class="widefat margin-top_22px">
			<thead>
				<tr>
					<th>'; _e( 'Buffer ID', 'bebop'); echo '</th>
					<th>'; _e( 'Secondary ID', 'bebop'); echo '</th>
					<th>'; _e( 'Activity Stream ID', 'bebop'); echo '</th>
					<th>'; _e( 'Username', 'bebop'); echo '</th>
					<th>'; _e( 'OER Type', 'bebop'); echo '</th>
					<th>'; _e( 'Imported', 'bebop'); echo '</th>
					<th>'; _e( 'Published', 'bebop'); echo '</th>
					<th>'; _e( 'Content', 'bebop'); echo '</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>'; _e( 'Buffer ID', 'bebop'); echo '</th>
					<th>'; _e( 'Secondary ID', 'bebop'); echo '</th>
					<th>'; _e( 'Activity Stream ID', 'bebop'); echo '</th>
					<th>'; _e( 'Username', 'bebop'); echo '</th>
					<th>'; _e( 'OER Type', 'bebop'); echo '</th>
					<th>'; _e( 'Imported', 'bebop'); echo '</th>
					<th>'; _e( 'Published', 'bebop'); echo '</th>
					<th>'; _e( 'Content', 'bebop'); echo '</th>
				</tr>
			</tfoot>
			<tbody>';
				
				foreach ( $oers as $oer ) {
				$extension = bebop_extensions::bebop_get_extension_config_by_name( $oer->type );
				echo '<tr>
					<td>' . $oer->id . '</td>' .
					'<td>' . $oer->secondary_item_id . '</td>' .
					'<td>' . $oer->activity_stream_id . '</td>' .
					'<td>' . bp_core_get_username( $oer->user_id ) . '</td>' .
					'<td>' . bebop_tables::sanitise_element( $extension['display_name'] ) . '</td>' .
					'<td>' . bp_core_time_since( $oer->date_imported ) . '</td>' .
					'<td>' . bp_core_time_since( $oer->date_recorded ) . '</td>' .
					'<td class="content">' . bebop_tables::sanitise_element( $oer->content ) . '</td>' .
				'</tr>';
				}
			echo '
			</tbody>
		</table>';
	}
	else {
		echo '<h4>' . ucfirst( $type ) . ' '; _e( 'OERs', 'bebop' ); echo '</h4>';
		echo '<p>'; _e( 'No content was found in the oer manager.', 'bebop' ); echo '</p>';
	}
		
	?>
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->