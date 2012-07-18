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
	}?>
    
    
    
    <!-- This overrides the current filter in the cookie to nothing "i.e. 
    	 on page refresh it will reset back to default" -->
    <script type="text/javascript">
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

