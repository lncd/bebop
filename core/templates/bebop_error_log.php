<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">

<?php include_once( 'admin_menu.php' ); ?>
<div id='bebop_admin_container'>

	<table class='bebop_table'>
		<tr class='nodata'>
			<th>Error ID</th>
			<th>Feed ID</th>
			<th>Timestamp</th>
			<th>Error Type</th>
			<th>Error Message</th>
		</tr>
		<?php
		$table_row_data = bebop_tables::fetch_table_data('bp_bebop_error_log');	
		foreach( $table_row_data as $row_data ) {
			echo "<tr>
				<td>" . bebop_tables::sanitise_element($row_data->id) . "</td>
				<td>" . bebop_tables::sanitise_element($row_data->feed_id) . "</td>
				<td>" . bebop_tables::sanitise_element($row_data->timestamp) . "</td>
				<td>" . bebop_tables::sanitise_element($row_data->error_type) . "</td>
				<td>" . bebop_tables::sanitise_element($row_data->error_message) . "</td>
			</tr>";
		}
		?>
	<!-- <End bebop_table -->
	</table>
<!-- End bebop_admin_container -->
</div>
