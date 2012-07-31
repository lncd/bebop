<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css"'
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<?php 
	if ( isset( $_GET['type'] ) ) {
		if ( strtolower( strip_tags( $_GET['type'] == 'unverified' ) ) ) {
			$type = 'unverified';
			$message = 'unverified';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'verified' ) ) ) {
			$type = 'verified';
			$message = 'verified';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'deleted' ) ) ) {
			$type = 'deleted';
			$message = 'deleted';
		}
	}
	else {
		$type = 'verified';
		$message = 'verified';
	}
	
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
	}
	else {
		echo '<p>No ' . $type . ' oers exist in the oer manager.</p>';
	}
		
	?>
	
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->