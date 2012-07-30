<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/user.css';?>" type='text/css'>
<div id='bebop_user_container'>
<?php 
$page = page_url( 2 );

if ( bp_is_my_profile() ) {
	if ( $page == '/bebop-oers/manager/' ) {
		include(WP_PLUGIN_DIR . '/bebop/core/templates/user/oer-manager.php');
	}
	else if ( $page == '/bebop-oers/providers/' ) {
		echo '<h3>OER Providers</h3>';
		if ( isset( $_GET['provider'] ) ) {
			include( WP_PLUGIN_DIR . '/bebop/extensions/' . $_GET['provider'] . '/templates/user-settings.php' );
		}
		else {
			$active_extensions = bebop_extensions::get_active_extension_names();
			if ( count( $active_extensions ) == 0 ) {
				echo '<p>No extensions are currently active. Please activate them in the bebop OER providers admin panel.</p>';
			}
			else {
				echo '<p>Choose an OER provider from the list below.</p>';
				foreach ( $active_extensions as $extension ) {
					echo '<div class="button_container"><a class="standard_button provider_button" href="?provider=' . $extension .'">' . ucfirst( $extension ) . '</a></div>';
				}
			}
		}
	}
}

if ( $page == '/bebop-oers/home/' ) {
	echo '<h3>Home</h3>';
	$_COOKIE['bp-activity-filter'] = 'all_oer';
	
	echo '<script type="text/javascript" src="' . WP_CONTENT_URL . '/plugins/bebop/core/resources/js/bebop_functions.js"></script>';
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
?>
</div> <!-- End bebop_user_container -->