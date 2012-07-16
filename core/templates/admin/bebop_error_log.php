<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . "/bebop/core/templates/admin/bebop_admin_menu.php" ); 

if( isset($_GET) ) {
	if( isset($_GET['clear_table']) ) {
		if( $table_row_data = bebop_tables::flush_table_data('bp_bebop_error_log') ) {
			echo '<div class="bebop_success_box">Table data cleared.</div>';
		}
		else {
			echo '<div class="bebop_error_box">Error clearing table data.</div>';
		}
	}
}
?>
<div id='bebop_admin_container'>
    <div class="postbox full_width center_margin margin-bottom_22px">
    	<h3>Bebop Errors</h3>
    	<div class="inside">
        	Logs any errors which the plugin has produced. Please report these to dmckeown-AT-lincoln-DOT-ac-DOT-uk (replace -AT- and -DOT- as necessary) 
        </div>
    </div>
	<?php
	
	$table_row_data = bebop_tables::fetch_table_data('bp_bebop_error_log');	
	if( count($table_row_data) > 0 ) {
		?>
		<div class='standard_class'><a class='options_button' href="<?php echo $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET); ?>&clear_table=true">Flush table data</a></div>
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
			foreach( $table_row_data as $row_data ) {
				echo "<tr>
					<td>" . bebop_tables::sanitise_element($row_data->id) . "</td>" . 								
					"<td>" . bebop_tables::sanitise_element($row_data->feed_id) . "</td>
					<td>" . bebop_tables::sanitise_element($row_data->timestamp) . "</td>
					<td>" . bebop_tables::sanitise_element($row_data->error_type) . "</td>
					<td>" . bebop_tables::sanitise_element($row_data->error_message) . "</td>
				</tr>";
			}
			?>
		<!-- <End bebop_table -->
		</table>
		<?php
	}
	else {
		echo "<div class='standard_class'>No data found in the error table.</div>";
	}
	?>
<!-- End bebop_admin_container -->
</div>