<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type="text/css">
<div id='bebop_user_container'>
<?php

global $bp;

$data_rows = bebop_tables::fetch_table_data('bp_bebop_oer_buffer');
foreach ($data_rows as $data) {
	var_dump($data);
}
echo '</div>';
?>