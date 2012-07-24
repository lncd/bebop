<?php if ( ( ! isset( $_GET['oer'] ) ) && ( ! isset( $_GET['action'] ) ) ) {  
	//Only shows if it is the users profile.  
	if ( bp_is_my_profile() ) {
		echo '<h3>User Settings</h3>';
		$activeExtensions = array();
		//get the active extension
		foreach ( bebop_extensions::get_extension_configs() as $extension ) {
			if ( bebop_tables::get_option_value( 'bebop_'.$extension['name'].'_provider' ) == 'on' ) {
				$activeExtensions[] = $extension['name'];
			}
		}
		
		if ( count( $activeExtensions ) == 0 ) {
			echo 'No extensions are currently active. Please activate them in the bebop OER providers admin panel.';
		}
		else {
			echo 'Choose an OER source from the sub menu above. ';
		}
	}
	$_COOKIE['bp-activity-filter'] = 'all_oer';
	
	echo "<script type='text/javascript' src='" . WP_CONTENT_URL . "/plugins/bebop/core/resources/js/bebop_functions.js'></script>";
	?>
	
	<!-- This overrides the current filter in the cookie to nothing "i.e.
		on page refresh it will reset back to default" -->
	<script type='text/javascript'>
		var scope = '';
		var filter = 'all_oer';
		
		<?php /*This function below deals with the first load of the activity stream in the OER page,
		it has been directly taken from the global.js buddypress file in the activity section
		and modified due to lack of pratical hooks. Taken from bp_activity_request(scope, filter).*/ ?>
		bebop_activity_cookie_modify( scope,filter );
	</script>
	<!-- This section creates the drop-down menu with its classes hooked into buddypress -->
	<div class='item-list-tabs no-ajax' id='subnav' role='navigation'>
		<ul class='clearfix'>
			<li id='activity-filter-select' class='last'>
				<label for='activity-filter-by'>Show:</label> 
				<select id='activity-filter-by'>
					<!-- This adds the hook from the main bebop file to add the extension filter -->
					<?php do_action( 'bp_activity_filter_options' ); ?>
				</select>
			</li>
		</ul>	
	</div>
	<!--This deals with pulling the activity stream -->
	<div class='activity' role='main'>
		<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>
	</div><!-- .activity -->
<?php
}
else {
	if ( isset( $_GET['action'] ) ) {
		if ( strtolower( $_GET['action'] ) == 'manage_oers/' ) {
			include(WP_PLUGIN_DIR . '/bebop/core/templates/user/oer_manager.php');
		}
	}
	else {
		include(WP_PLUGIN_DIR . '/bebop/extensions/' . $_GET['oer'] . '/templates/user_settings.php');
	}
}
?>