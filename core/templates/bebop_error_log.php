<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( 'admin_menu.php' ); ?>
<div id='bebop_admin_container'>
        <div class="postbox width_98 margin-bottom_22px">
        	<h3>Bebop Errors</h3>
        	<div class="inside">
            	Logs any errors which the plugin has produced. Please report these to dmckeown-AT-lincoln-DOT-ac-DOT-uk (replace -AT- and -DOT- as necessary) 
            </div>
        </div>
		<div class="clear"></div>

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
				<td>" . bebop_tables::sanitise_element($row_data->id) . "</td>" . 								//Yeah I am English :P
				"<td>" . bebop_tables::sanitise_element($row_data->feed_id) . "</td>
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