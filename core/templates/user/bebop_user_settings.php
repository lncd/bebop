<h3>
<?php if(!isset($_GET['eor'])){
    echo "<h3>Provider</h3>";
    echo "Provider Info <br/><br/>";
}
?>
</h3>

<div class="buddystream_album_navigation_links">
    <ul>
        <?php

        //get the active extension
        foreach( bebop_extensions::get_extension_configs() as $extension ) {
            if(bebop_tables::get_option('bebop_'.$extension['name'].'_provider') == "on" && bebop_tables::get_option('bebop_'.$extension['name'].'_setup')){
                echo '<li><a href="?eor=' . $extension['name'] . '">'.ucfirst($extension['displayname']).'</a></li>';         
                $activeExtensions[] = $extension['name'];
            }
			else {
				echo "error with " . $extension['name'];
			}
        }
        ?>
    </ul>
</div>
<br/><br/>
<div>

<?php
if(isset($_GET['oer'])){
	
    include(WP_PLUGIN_DIR."/bebop/extensions/".$_GET['eor']."/templates/user_settings.php");
}
?>
</div>