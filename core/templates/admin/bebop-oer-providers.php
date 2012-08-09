<link rel='stylesheet' href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type='text/css'>
<link rel='shortcut icon' href="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>">

<?php
//load the individual admin page
if ( isset( $_GET['provider'] ) ) {
	bebop_extensions::page_loader( $_GET['provider'] );
}
else {
	global $bp;
	if ( isset( $_POST['submit'] ) ){
		//reset the importer queue
		bebop_tables::update_option( 'bebop_importers_queue', '' );
		
		//set the new importer queue
		$importerQueue = array();
		foreach ( bebop_extensions::get_extension_configs() as $extension ) {
			if ( is_array( $extension ) && isset( $_POST['bebop_' . $extension['name'] . '_provider'] ) && $_POST['bebop_' . $extension['name'] . '_provider'] == 'on' ) {
				$importerQueue[] = $extension['name'];
			}
		}
		bebop_tables::update_option( 'bebop_importers_queue', implode( ',', $importerQueue ) );
		echo '<div class="bebop_success_box">Settings Saved.</div>';
	}
	include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
	<div id='bebop_admin_container'>
		
		<div class='postbox center_margin margin-bottom_22px'>
			<h3>OER Providers</h3>
			<div class="inside">
				Here you can manage installed OER extensions. To enable an extension, click the "enabled" checkbox and click "Save Changes". The "Admin Settings" link can now be clicked to 
				change any configuration settings the extension might require, such as API keys and import limits.
			</div>
		</div>
		
		<form method='post' class='bebop_admin_form no_border'>
		<table class='bebop_table'>
			<tr class='nodata'>
				<th>Extension Name</th>
				<th>Users</th>
				<th colspan=>Unverified OERs</th>
				<th colspan=>Verified OERs</th>
				<th colspan=>Deleted OERs</th>
				<th colspan='2'>Options</th>
			</tr>
			
			<?php
			//loop throught extensions directory and get all extensions
			foreach ( bebop_extensions::get_extension_configs() as $extension ) {
				if ( is_array( $extension ) ) {
					//does it need a parent if so does parent exists
					$loadExtension = true;
					
					if ( isset( $extension['parent'] ) ) {
						if ( ! bebop_extensions::extension_exist( $extension['parent'] ) ) {
							$loadExtension = false;
						}
					}
					
					if ( $loadExtension ) {
						if ( isset( $_POST['submit'] ) ) {
							if ( isset( $_POST['bebop_' . $extension['name'] . '_provider'] ) ) {
								bebop_tables::update_option( 'bebop_' . $extension['name'] . '_provider', trim( $_POST['bebop_' . $extension['name'] . '_provider'] ) );
							}
							else {
								bebop_tables::update_option( 'bebop_' . $extension['name'] . '_provider', '' );
							}
						}
						echo '<tr>
							<td>' . ucfirst( $extension['name'] ) . '</td>
							<td>' . bebop_tables::count_users_using_extension( $extension['name'] ) . '</td>
							<td><a href="?page=bebop_oers&type=unverified">' . bebop_tables::count_oers_by_extension( $extension['name'], 'unverified' ) . '</a></td>
							<td><a href="?page=bebop_oers&type=verified">' . bebop_tables::count_oers_by_extension( $extension['name'], 'verified' ) . '</a></td>
							<td><a href="?page=bebop_oers&type=deleted">' . bebop_tables::count_oers_by_extension( $extension['name'], 'deleted' ) . '</a></td>
							<td>';
							echo "<label for='bebop_" . $extension['name'] . "_provider'>Enabled:</label><input id='bebop_" .$extension['name'] . "_provider' name='bebop_".$extension['name'] . "_provider' type='checkbox'";
							if ( bebop_tables::get_option_value( 'bebop_' . $extension['name'] . '_provider' ) == 'on' ) {
								echo 'CHECKED';
							}
							echo '></td>
							<td><a href="?page=bebop_oer_providers&provider=' . strtolower( $extension['name'] ) . '">Settings</a></td>
						</tr>';
					}
				}
			}
			?>
			</table>
			<div class='button_container'><button id='submit' name='submit'>Save Changes</button></div>
		</form>
	<!-- End bebop_admin_container -->
	</div>
<?php
}