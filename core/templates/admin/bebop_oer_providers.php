<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php
    global $bp;
    if( isset($_POST['submit']) ){
        var_dump($_POST);
        //reset the importer queue
        bebop_tables::update_option("bebop_importers_queue", "");
        
        //set the new importer queue
        $importerQueue = array();
        foreach ( bebop_extensions::get_extension_configs() as $extension ) {
            if( is_array($extension) && isset($_POST['bebop_' . $extension['name'] . '_provider']) && $_POST['bebop_' . $extension['name'] . '_provider'] == "on" ){
                $importerQueue[] = $extension['name'];
            }
        }
        bebop_tables::update_option("bebop_importers_queue", implode(",", $importerQueue));
        
        echo '<div class="bebop_success_box">Settings Saved.</div>';
    }
?>
		
<?php include_once( WP_PLUGIN_DIR . "/bebop/core/templates/admin/bebop_admin_menu.php" ); ?>
	<div id='bebop_admin_container'>
	<form id="settings_form" action="" method="post">
	<table class='bebop_table'>
		
		<tr class='nodata'>
			<th>Extension Name</th>
			<th># of Users</th>
			<th># of OER's</th>
			<th colspan='2'>Options</th>
		</tr>
	    
	    <?php
	    //loop throught extensions directory and get all extensions
	    foreach ( bebop_extensions::get_extension_configs() as $extension ) {
	
	        if( is_array($extension) ) {
	
	            //does it need a parent if so does parent exists
	            $loadExtension = true;
	
	            if( isset($extension['parent']) ) {
	                if( ! bebop_extensions::extension_exist($extension['parent']) ) {
	                    $loadExtension = false;
	                }
	            }
	
	            if( $loadExtension ){
	                if( isset( $_POST['bebop_' . $extension['name'] . '_provider'] ) ) {
	                	bebop_tables::update_option('bebop_' . $extension['name'] . '_provider', trim($_POST['bebop_' . $extension['name'] . '_provider']));
						echo "done";
	                }
					echo "<tr>
						<td>" . ucfirst($extension['name']) . "</td>
						<td>" . bebop_tables::count_users_using_extension($extension['name']) . "</td>
						<td>" . bebop_tables::count_oers_by_extension($extension['name']) . "</td>
						<td>";
						echo 'Enabled: <input id="bebop_'.$extension['name'] . '_provider" type="checkbox" name="bebop_'.$extension['name'] . '_provider"';
						if( bebop_tables::get_option_value('bebop_' . $extension['name'] . '_provider') == 'on' ) {
							echo 'CHECKED';
						}
						echo "></td>
						<td>";
						if( bebop_tables::get_option_value('bebop_' . $extension['name'] . '_provider') == 'on' ) {
              				echo '<a href="?page=bebop_' . $extension['name'] . '">Admin Settings</a>';
						}
						echo"</td>
					</tr>";			
	            }
	        }
	    }
	    ?>
	    </table>
	    <div style="float:left; clear:both;">
		    <input type="submit" name="submit" class="button-primary" value="Save Changes">
		</div>
	    </form>
	   
	    

<!-- End bebop_admin_container -->
</div>