<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type="text/css">
<div id='bebop_user_container'>
<h3> OER Manager</h3>
Here you can manage your OER's. Change the filter to switch between approved content, removed content, and unverified content.
<?php

global $bp;

$data_rows = bebop_tables::fetch_oer_data($bp->loggedin_user->id, 'bp_bebop_oer_buffer');

if( count($data_rows) > 0 ) {
	echo "<table class='bebop_user_table'>
		<tr>
			<th>Type</th>
			<th>Published</th>
			<th>Content</th>
			<th>Options</th>
		</th>";
		
	foreach ($data_rows as $data) {
		echo "<tr>
			<td>" . bebop_tables::sanitise_element(ucfirst($data->type)) . "</td>" .
			"<td>" . time_since($data->date_recorded) . "</td>" .
			"<td>" . bebop_tables::sanitise_element($data->content) . "</td>" .
		"</tr>";
	}
	echo "</table>";
}
else {
	echo "We could not find any OERs";
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