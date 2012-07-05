
<?php if( ! isset($_GET['oer']) ) {
    echo "<h3>User Settings</h3>";
	
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
				echo "Choose an OER source from the sub menu above. ";
			}
	        ?>
	    </ul>
	</div>
<?php
}
else {
	include(WP_PLUGIN_DIR."/bebop/extensions/" . $_GET['oer'] . "/templates/user_settings.php");
}
?>