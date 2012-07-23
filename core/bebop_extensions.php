<?php

class bebop_extensions {
	
	function load_extensions() {
		$handle = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
		if ( $handle ) {
			while ( false !== ( $file = readdir($handle) ) ) {
				if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
					if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/core.php' ) ) {
						include( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/core.php' );
					}
				}
			}
		}
	}
	
	function get_extension_configs() {
		$config = array();
		$handle = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
		
		if ( $handle ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
					if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/config.php' ) ) {
						if ( ! function_exists( 'get_' . $file . '_config' ) ) {
							require( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/config.php' );
						}
						$config[] = call_user_func( 'get_' . $file . '_config' );
					}
				}
			}
		}
		return $config;
	}
	
	function extension_exist( $extensions ) {
		if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . strtolower( $extensions ) . '/core.php' ) ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function page_loader( $extension ) {
		$extension = strtolower( $extension );
		if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/config.php' ) ) {
			if ( ! function_exists( 'get_' . $extension . '_config' ) ) {
				require( WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/config.php' );
			}
			$config = call_user_func( 'get_' . $extension . '_config' );
		}
		if ( ! isset( $_GET["settings"] ) ) {
			 $page = strtolower( $config['defaultpage'] );
		}
		else {
			$page = strtolower( $_GET["settings"] );
		}
		
		if ( ! empty( $_GET['child'] ) ) {
			$extension = $_GET['child'];
		}
		include WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/templates/admin_' . $page.  '.php';
	}
	
	function user_page_loader( $extension, $page = 'settings' ) {
		global $bp;
		if ( $bp->displayed_user->id != $bp->loggedin_user->id && $page !='album' ) {
			header( 'location:' . get_site_url() );
		}
		add_action( 'bp_template_content', 'bebop_user_'.$page.'_screen_content' );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}
}