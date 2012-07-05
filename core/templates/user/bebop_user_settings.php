
<?php if( ! isset($_GET['oer']) ) {
    echo "<h3>Provider</h3>";
    echo "Provider Info";
}
?>

<div>
    <ul>
        <?php

        //get the active extension
        foreach( bebop_extensions::get_extension_configs() as $extension ) {
            if(bebop_tables::get_option_value('bebop_'.$extension['name'].'_provider') == "on") {     
                $activeExtensions[] = $extension['name'];
            }
        }
		if( count($activeExtensions) == 0 ) {
			echo "No extensions are currently active. Please activate them in the bebop OER provides admin panel.";
		}
		else {
			echo "Choose a provider from the list above.";
		}
        ?>
    </ul>
</div>