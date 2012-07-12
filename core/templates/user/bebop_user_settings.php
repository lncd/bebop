<?php if( ! isset($_GET['oer']) ) {  
	
	//Only shows if it is the users profile.  
	if(bp_is_my_profile())
	{
		echo "<h3>User Settings</h3>";
		
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

	/*This function overrides the current query string and sets it to null to ensure
	  the current drop down menu is not attempted to be matched with ones from the activity stream etc. */
	function dropdown_query_override ( $query_string ) {
		$query_string = '';
		return $query_string;
	}
		
	//Adds the filter to the function.
	add_filter( 'bp_ajax_querystring', 'dropdown_query_override' );?>
    
      
    <!-- This section creates the drop-down menu with its classes hooked into buddypress -->
    <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<ul class="clearfix">
			<li id="activity-filter-select" class="last">		
				<label for="activity-filter-by">Show:</label> 
				<select id="activity-filter-by">
					<option value="-1">Everything</option>
					<!-- This adds the hook from the main bebop file to add the extension filter -->
					<?php do_action( 'bp_activity_filter_options' ); ?>
				</select>
			</li>
		</ul>	
	</div>

	<!-- This is the class which will be refreshed when a different menu item is selected -->
    <div class="activity" role="main">
        <?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ){ ?>

			<?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing */ ?>
			<noscript>
				<div class="pagination">
					<div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
					<div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
				</div>
			</noscript>
				
<?php		//This section adds the list styles on the first run. 
			if ( empty( $_POST['page'] ) ) { 
				echo '<ul id="activity-stream" class="activity-list item-list">';
			}			
				//This section loops through the query records and uses the buddypress activity/entry to output them.
				while ( bp_activities() ) {	
			 		bp_the_activity();
		 			locate_template( array( 'activity/entry.php' ), true, false );
				}

			//This ends the list section for style on the first run.
			if ( empty( $_POST['page'] ) ) {
				echo '</ul>';
			} 
		} 
		else {?>

			<div id="message" class="info">
				<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
			</div>
<?php	}
	//Closes the record import div
	echo '</div>';
}
else {
	include(WP_PLUGIN_DIR."/bebop/extensions/" . $_GET['oer'] . "/templates/user_settings.php");
}
?>

