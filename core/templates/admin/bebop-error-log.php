<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class='postbox full_width center_margin margin-bottom_22px'>
		<h3>Bebop Errors</h3>
		<div class='inside'>
			Logs any errors which the plugin has produced. Please report these to dmckeown-AT-lincoln-DOT-ac-DOT-uk (replace -AT- and -DOT- as necessary) 
		</div>
	</div>
	<?php
	$table_row_data = bebop_tables::fetch_table_data( 'bp_bebop_error_log' );
	if ( count( $table_row_data ) > 0 ) {
		?>
		<div class='button_container'><a class='options_button' href="<?php echo $_SERVER['PHP_SELF'] . '?' . http_build_query( $_GET ); ?>&clear_table=true">Flush table data</a></div>
		<div class='clear'></div>
		
		<table class='bebop_table'>
			<tr class='nodata'>
				<th>Error ID</th>
				<th>Timestamp</th>
				<th>Error Type</th>
				<th>Error Message</th>
			</tr>
			<?php
			foreach ( $table_row_data as $row_data ) {
				echo '<tr>
					<td>' . bebop_tables::sanitise_element( $row_data->id ) . '</td>
					<td>' . bebop_tables::sanitise_element( $row_data->timestamp ) . '</td>
					<td>' . bebop_tables::sanitise_element( $row_data->error_type ) . '</td>
					<td>' . bebop_tables::sanitise_element( $row_data->error_message ) . '</td>
				</tr>';
			}
			?>
		<!-- <End bebop_table -->
		</table>
		<?php
	}
	else {
		echo "No data found in the error table.";
	}
	?>
<!-- End bebop_admin_container -->
</div>