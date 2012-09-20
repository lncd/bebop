<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class='bebop_admin_box'>
		<img class='bebop_logo' src="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_logo.png';?>">
		<p><?php _e( 'Welcome to the OER plugin for BuddyPress. Developed by <a href="http://www.lncd.lincoln.ac.uk">LNCD @ the University of Lincoln</a>.', 'bebop' ); ?></p>
		<p><?php _e( 'Bebop was designed for academic institutions who want to incorporate Open Educational Resources into BuddyPress Profiles. This plugin aids the discovery of OERs in the BuddyPress environment', 'bebop' ); ?></p>
		<div class="clear"></div>
	</div>
	
	<div class='postbox-container'>
		<div class='postbox'>
			<h3><?php _e( 'Latest News' ); ?></h3>
			<div class='inside'>
				<p><?php _e( 'Version 1.1 of Bebop has been released. Many requested features have been implemented, bugs have been fixed, and issues resolved. For more details, please see the changelog in README.txt.', 'bebop' ); ?></p>
				<p><?php _e( 'Version 1.0 of Bebop has now been released. This BuddyPress plugin allows users to import Open Educational Resources from around the web, into their BuddyPress activity stream.</p>', 'bebop' ); ?>
			</div>
		</div>

		<div class="postbox">
			<h3><?php _e( 'Support', 'bebop' ); ?></h3>
			<div class="inside">
				<?php _e( 'While we cannot guarantee official support, we will always do what we can to help people sing this plugin. For support, please see our <a target="_blank" href="https://github.com/lncd/bebop/wiki">Github Wiki</a>.', 'bebop' ); ?>
			</div>
		</div>
	<!-- End postbox-container -->
	</div>
	
	<div class="postbox-container">
		<div class='postbox'>
			<h3><a href="?page=bebop_oers&type=verified"><?php _e( 'Recently Verified Content', 'bebop' ); ?></a></h3>
			<div class='inside'>
				<?php
				$oers = bebop_tables::admin_fetch_oer_data( 'verified', 20 );
				
				if ( count( $oers ) > 0 ) {
					echo '<table class="postbox_table">
						<tr class="nodata">
							<th>'; _e( 'Username', 'bebop' );  echo '</th>
							<th>'; _e( 'Type', 'bebop' );  echo '</th>
							<th>'; _e( 'Imported', 'bebop' );  echo '</th>
							<th>'; _e( 'Published', 'bebop' );  echo '</th>
							<th>'; _e( 'Content', 'bebop' );  echo '</th>
						</tr>';
					
					foreach ( $oers as $oer ) {
						echo '<tr>
							<td>' . bp_core_get_username( $oer->user_id ) . '</td>' .
							'<td>' . bebop_tables::sanitise_element( ucfirst( $oer->type ) ) . '</td>' .
							'<td>' . bp_core_time_since( $oer->date_imported ) . '</td>' .
							'<td>' . bp_core_time_since( $oer->date_recorded ) . '</td>' .
							'<td class="content">' . bebop_tables::sanitise_element( $oer->content, $allow_tags = true ) . '</td>' .
						'</tr>';
					}
					echo '</table>';
				}
				else {
					echo '<p>'; _e( 'No verified oers exist in the oer manager.', 'bebop' );  echo '</p>';
				}
				?>
				
			</div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->