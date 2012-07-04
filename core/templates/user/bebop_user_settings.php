
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
                echo '<li><a href="?oer=' . $extension['name'] . '">'.ucfirst($extension['displayname']).'</a></li>';         
                $activeExtensions[] = $extension['name'];
            }
        }
		if( count($activeExtensions) == 0 ) {
			echo "No extensions are currently active. Please activate them in the bebop OER provides admin panel.";
		}
        ?>
    </ul>
</div>

<?php
if( isset($_GET['oer']) ) {
    include(WP_PLUGIN_DIR."/bebop/extensions/". strtolower($_GET['oer']) ."/templates/user_settings.php");
}
?>