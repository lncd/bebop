<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css"'
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<?php 
	if ( isset( $_GET['type'] ) ) {
		if ( strtolower( strip_tags( $_GET['type'] == 'unverified' ) ) ) {
			$type = 'unverified';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'verified' ) ) ) {
			$type = 'verified';
		}
		else if ( strtolower( strip_tags( $_GET['type'] == 'deleted' ) ) ) {
			$type = 'deleted';
		}
	}
	else {
		$type = 'verified';
	}
	
	$oers = bebop_tables::admin_fetch_oer_data( $type );
	
	if ( count( $oers ) > 0 ) {
		echo '<h4>' . ucfirst( $type ) . ' OERs</h4>
		<table class="bebop_table">
			<tr class="nodata">
				<th>User</th>
				<th>OER Type</th>
				<th>Published</th>
				<th>Imported</th>
				<th>Content</th>
			</tr>';
		
		foreach ( $oers as $oer ) {
			echo '<tr>
				<td>' . bp_core_get_username( $oer->user_id ) . '</td>' .
				'<td>' . bebop_tables::sanitise_element( ucfirst( $oer->type ) ) . '</td>' .
				'<td>' . time_since( $oer->date_recorded ) . '</td>' .
				'<td>' . time_since( $oer->date_imported ) . '</td>' .
				'<td class="content">' . bebop_tables::sanitise_element( $oer->content ) . '</td>' .
			'</tr>';
		}
	}
	else {
		echo '<p>Unfortunately, we could not find any ' . $type . ' oers for you to manage.</p>';
	}
		
	?>
	
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->