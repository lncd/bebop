<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">
<link rel="shortcut icon" href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( 'bebop_admin_menu.php' ); ?>
<div id='bebop_admin_container'>
	
	<form id="settings_form" action="" method="post">
	
	<?php
	    global $bp;
	    if($_POST['submit']){ 
	        
	        //reset the importer queue
	        update_site_option("bebop_importers_queue", "");
	        
	        //set the new importer queue
	        foreach ( bebop_extensions::get_extension_configs() as $extention ) {
	            if(is_array($extention) && isset($_POST['bebop_'.$extention['name'].'_provider']) && $_POST['bebop_'.$extention['name'].'_provider'] == "on"){
	                $importerQueue[] = $extention['name'];
	            }
	        }
	        
	        update_site_option("bebop_importers_queue", implode(",", $importerQueue));
	        
	        echo '<div>Settings Saved</div>'; 
	    }
	?>  
	    
	    <div class="metabox-holder">    
	    <?php
	    
	    //loop throught extentions directory and get all extentions
	    foreach ( bebop_extensions::get_extension_configs() as $extention ) {
	
	        if(is_array($extention)){
	
	            //does it need a parent if so does parent exists
	            $loadExtension = true;
	
	
	            if( $extention['parent'] ){
	                if( ! bebop_extensions::extension_exist($extention['parent'])){
	                    $loadExtension = false;
	                }
	            }
	
	            if( $loadExtension ){
	                //define vars
	                define('bebop_'.$extention['name'].'_provider', "");
	
	                if( $_POST){
	                    delete_site_option('bebop_'.$extention['name'].'_provider');
	                    update_site_option('bebop_'.$extention['name'].'_provider', trim($_POST['bebop_'.$extention['name'].'_provider']));
	                }
	
	                echo '
	                   <div class="postbox" style="float:left; width:200px; margin-right:20px;">
	                        <div><h3 style="cursor:default; font-family:arial; font-size:13px; font-weight:bold;"><span class="admin_icon '.$extention['name'].'"></span> '.__(ucfirst($extention['displayname']), 'buddystream').'</h3>
	                            <div class="inside" style="padding:10px;">
	                                <input id="bebop_'.$extention['name'].'" class="switch icons" type="checkbox" name="bebop_'.$extention['name'].'_power" />
	                            </div>
	                        </div>
	                    </div>
	                ';
	            }
	        }
			else {
				echo 'error occured loading extensions';
			}
	    }
	    ?>
	    </div>
	        
	
		<div style="float:left; clear:both;">
		    <input type="submit" name="submit" class="button-primary" value="Save Changes">
		</div>
	
	</form>

<!-- End bebop_admin_container -->
</div>