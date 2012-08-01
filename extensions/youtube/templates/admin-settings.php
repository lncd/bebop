<?php
//update section
if ( isset( $_POST['submit'] ) ) {
	bebop_tables::update_option( 'bebop_youtube_maximport', trim( strip_tags( strtolower( $_POST['bebop_youtube_maximport'] ) ) ) );

	echo '<div class="bebop_success_box">Settings Saved.</div>';
}

//remove the user
if ( isset( $_GET['reset_user_id'] ) ) {
	$user_id = trim( $_GET['reset_user_id'] );
	bebop_tables::remove_user_from_provider( $user_id, 'youtube' );
	
	echo '<div class="bebop_success_box">User has been removed.</div>';
}

include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class='postbox center_margin margin-bottom_22px'>
		<h3>Youtube Settings</h3>
		<div class="inside">
			Settings required for youtube syncronisation.
		</div>
	</div>
	<form class='bebop_admin_form' method='post'>
		<fieldset>  
			<span class='header'>Youtube Settings</span>
			<label for='bebop_youtube_maximport'>Maximum amount of imports:</label>
			<input type='text' id='bebop_youtube_maximport' name='bebop_youtube_maximport' value='<?php echo bebop_tables::get_option_value( 'bebop_youtube_maximport' ); ?>' size='5'>
		</fieldset>
		<div class='bebop_button_container'><button id='submit' name='submit'>Save Changes</button></div>	
	</form>
	<?php
	$user_metas = bebop_tables::get_user_ids_from_meta_name( 'bebop_youtube_username' );
	if ( count( $user_metas ) > 0 ) {
		?>
		<table class='bebop_settings_table'>
			<tr class='nodata'>
				<th colspan='5'>Youtube Users</th>
			</tr>
			
			<tr class='nodata'>
				<td class='bold'>User ID</td>
				<td class='bold'>Username</td>
				<td class='bold'>User email</td>
				<td class='bold'>Youtube Channel</td>
				<td class='bold'>Options</td>
			</tr>
			<?php	
			
			foreach ( $user_metas as $user ) {	
				$this_user = get_userdata( $user->user_id );
				echo '<tr>
					<td>' . bebop_tables::sanitise_element( $user->user_id ) . '</td>
					<td>' . bebop_tables::sanitise_element( $this_user->user_login ) . '</td>
					<td>' . bebop_tables::sanitise_element( $this_user->user_email ) . '</td>
					<td>' . bebop_tables::sanitise_element( bebop_tables::get_user_meta_value( $user->user_id, 'bebop_youtube_username' ) ) . "</td>
					<td><a href='?page=bebop_oer_providers&provider=youtube&reset_user_id=" . bebop_tables::sanitise_element( $user->user_id ) . "'>Reset User</a></td>
				</tr>";
			}
		?>
		<!-- <End bebop_table -->
		</table>
		<?php
	}
	else {
		echo "No users found for the Youtube extension.";
	}
	?>
<!-- End bebop_admin_container -->
</div>