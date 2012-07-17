<?php if( ( ! isset($_GET['oer']) ) && ( ! isset($_GET['action']) ) ) {  

	//Only shows if it is the users profile.  
	if(bp_is_my_profile())
	{
		echo "<h3>User Settings</h3>";
		$activeExtensions = array();
       	//get the active extension
       	foreach( bebop_extensions::get_extension_configs() as $extension ) {
		    if(bebop_tables::get_option_value('bebop_'.$extension['name'].'_provider') == "on") {     
            	$activeExtensions[] = $extension['name'];
           	}
        }
		
		if( count($activeExtensions) == 0 ) {
			echo "No extensions are currently active. Please activate them in the bebop OER providers admin panel.";
		}
		else {
			echo "Choose an OER source from the sub menu above. ";
		}
	}
	
	/*This function overrides the current query string and sets it to all the OER to ensure
	  the current drop down menu is not attempted to be matched with ones from the activity stream etc. */
	function dropdown_query_override ( $query_string ) {
		
		$string_build = '';	
				
		//Loops through all the different extensions and adds the active extensions to the temp variable.
   	  	foreach( bebop_extensions::get_extension_configs() as $extension ) {
	  		if(bebop_tables::get_option_value('bebop_'.$extension['name'].'_provider') == "on") {     
           		$string_build .= $extension['name'] . ',';
       		}
   		}
			
		/*Checks to make sure the string is not empty and if it is then simply returns all_oer which results in
		  nothing being shown. */
		if($string_build !== '')
		{			
			//Removes the end ","
			$string_build = substr($string_build, 0,-1);				
				
			//Recreates the query string with the new views.
			$query_string = 'type=' . $string_build . '&action=' . $string_build;
		}		

		return $query_string;
	}?>
    
    
    
    <!-- This overrides the current filter in the cookie to nothing "i.e. 
    	 on page refresh it will reset back to default" -->
    <script type="text/javascript">
    jQuery.cookie
		jQuery.cookie('bp-activity-filter', "all_oer");	
	</script>  
      
    <!-- This section creates the drop-down menu with its classes hooked into buddypress -->
    <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<ul class="clearfix">
			<li id="activity-filter-select" class="last">		
				<label for="activity-filter-by">Show:</label> 
				<select id="activity-filter-by">
					<!-- This adds the hook from the main bebop file to add the extension filter -->
					<?php do_action( 'bp_activity_filter_options' ); ?>
				</select>
			</li>
		</ul>	
	</div>
		<!--This deals with pulling the activity stream -->
		<div class="activity" role="main">
				<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
			</div><!-- .activity -->
<?php }
?>

