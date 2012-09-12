<?php

	bebop_tables::update_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_done_initial_import', 0 );
	bebop_tables::add_user_meta( $bp->loggedin_user->id, $extension['name'], 'bebop_' . $extension['name'] . '_' . $_POST['bebop_' . $extension['name'] . '_username'] . '_done_initial_import', 0 );
	
	if( bebop_tables::get_user_meta_value( $specific_user, 'bebop_' . $this_extension['name'] . '_done_initial_import' ) == 0 ) {
			bebop_tables::update_user_meta( $specific_user, $this_extension['name'], 'bebop_' . $this_extension['name'] . '_done_initial_import', 1 );
			//declare as array so we do not have to modify the foreach statement.
			$user_metas = array();
			
			$user = new stdClass();
			$user->user_id = $specific_user;
			$user_metas[] = $user;
		}
		else {
			bebop_tables::log_error( 'Importer - ' . ucfirst( $this_extension['name'] ), 'Someone tried to force an initial import for a user_id/feed name that has already done its first import. (possible hack attempt?). user_id:' .
			$specific_user );
		}
?>