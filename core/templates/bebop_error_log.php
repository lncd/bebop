<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">

<?php include_once( 'admin_menu.php' ); 
	

	$table_row_data = bebop_tables::fetch_table_data('bp_bebop_error_log');	
	foreach( $table_row_data as $row_data ) {
		echo 'new row: <pre>';
		var_dump($row_data);
		echo '</pre>';
	}
?>
