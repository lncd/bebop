<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type="text/css">
<div id='bebop_user_container'>
<h3> OER Manager</h3>
<p>Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.</p>
<?php

global $bp;

$unverified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'unverified');
$verified_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'verified');
$removed_oers = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer', 'removed');


if( count($unverified_oers) > 0 ) {
	echo '<h4> Unverified OERs</h4>';
	echo "<table class='bebop_user_table'>
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
		"</tr>";
	}
	echo "</table>";
}
else {
	echo "<p>Unfortunately, we could not find any OERs to manage.</p>";
}
echo '</div>';


function time_since($date) {
	$date = strtotime($date);
	$since = time() - $date;
	
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }
    $print = ($count == 1) ? '1 '.$name : "$count {$name}s ago";
    return $print;
}
?>