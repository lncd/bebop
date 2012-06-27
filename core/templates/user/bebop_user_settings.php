<h3>
<?php if(!isset($_GET['oer'])){
    echo "<h3>Provider</h3>";
    echo "Provider Info <br/><br/>";
}
?>
</h3>

<div class="buddystream_album_navigation_links">
    <ul>
        <?php
		$count_check = 0;
        //get the active extension
               		
        foreach( bebop_extensions::get_extension_configs() as $extension ) {
        	if(bebop_tables::get_option('bebop_'.$extension['name'].'_provider') == "on") {
                	echo '<li><a href="?eor=' . $extension['name'] . '">'.ucfirst($extension['displayname']).'</a></li>';         
                	$activeExtensions[] = $extension['name'];
            	}
				else {
					$count_check++;
				}			
        	}
		
		if($count_check===count(bebop_extensions::get_extension_configs())) {
				echo "No extensions are currently active. Please activate them in the bebop OER provides admin panel.";
		}
        ?>
    </ul>
</div>
<br/><br/>
<div>

<?php
if(isset($_GET['oer'])){
	
    include(WP_PLUGIN_DIR."/bebop/extensions/".$_GET['oer']."/templates/user_settings.php");
}
?>
</div>