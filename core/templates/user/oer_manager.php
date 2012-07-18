<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type="text/css">
<div id='bebop_user_container'>
	
<?php

if(isset($_POST)) {
	var_dump($_POST);
}

?>

<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<?php

global $bp;

$unverified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'unverified');
$verified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'verified');
$removed_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'removed');


if( count($unverified_oers) > 0 ) {
	echo '<h4> Unverified OERs</h4>';
	echo "<form method='post'>
	<table class='bebop_user_table'>
		<tr>
			<th>Type</th>
			<th>Published</th>
			<th>Content</th>
			<th>Options</th>
		</tr>";
		
	foreach ($unverified_oers as $unverified_oer) {
		echo "<tr>
			<td>" . bebop_tables::sanitise_element(ucfirst($unverified_oer->type)) . "</td>" .
			"<td>" . time_since($unverified_oer->date_recorded) . "</td>" .
			"<td>" . bebop_tables::sanitise_element($unverified_oer->content) . "</td>" .
			"<td><label for='" .$unverified_oer->type . "'></label><input id='" . $unverified_oer->type . "' name='" . $unverified_oer->type . "' type='checkbox'></td>" .
		"</tr>";
	}
	echo "</table>
	</form>";
}
else {
	echo "<p>Unfortunately, we could not find any OERs to manage.</p>";
}
echo '</div>';
?>