<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type="text/css">
<div id='bebop_user_container'>
	
<?php

if(isset($_POST)) {
	var_dump($_POST);
	echo '<br><br>';
	if(isset($_POST['action'])) {
		if($_POST['action'] == 'verify') {
			foreach (array_keys($_POST) as $oer) {
				if($oer != 'action') {
					$data = bebop_tables::fetch_individual_oer_data($oer);
					if( ! empty($data['id']) ) {
						var_dump($data);
					}
				}
			}
		}
		else if ($_POST['action'] == 'delete') {
		}
	}
}

?>

<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<?php

global $bp;

$unverified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'unverified');
$verified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'verified');
$removed_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'removed');


if( count($unverified_oers) > 0 ) {
	echo "<form class='bebop_user_form' method='post'>";
	echo "<h4> Unverified OERs</h4>
	<table class='bebop_user_table width_90'>
		<tr>
			<th>Type</th>
			<th>Published</th>
			<th>Content</th>
			<th>Select</th>
		</tr>";
		
	foreach ($unverified_oers as $unverified_oer) {
		echo "<tr>
			<td>" . bebop_tables::sanitise_element(ucfirst($unverified_oer->type)) . "</td>" .
			"<td>" . time_since($unverified_oer->date_recorded) . "</td>" .
			"<td>" . bebop_tables::sanitise_element($unverified_oer->content) . "</td>" .
			"<td class='checkbox_container'><div class='checkbox'><input type='checkbox' id='" . $unverified_oer->secondary_item_id . "' name='" . $unverified_oer->secondary_item_id . "'></div></td>" .
		"</tr>";
	}
	echo "</table>";
	echo '
		<h4>Action</h4>
		<label for="verify">Verify:</label><input type="radio" name="action" id="verify" value="verify"><br>
		<label for="delete">Delete:</label><input type="radio" name="action" id="delete" value="delete"><br>
		
		<input type="submit" class="button_auth" value="Submit">
	</form>';
}
else if( count($verified_oers) > 0 ) {
}
else if( count($removed_oers) > 0 ) {
}
else {
	echo "<p>Unfortunately, we could not find any OERs to manage.</p>";
}
echo '</div>';
?>