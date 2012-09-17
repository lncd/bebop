<?php

class bebop_extensions {
	
	function bebop_register_extension( $path ) {
	add_filter( 'bebop_plugin_extensions', create_function( '$extensions', '
	$extensions[] = "' . $path . '";
	return $extensions;
	' ) );
	}
	
	function bebop_gather_extensions() {
		
		//core extensions are stored in bebop/extensions. 3rd party developers should store extensions elsewhere, or Bebop will overwrite them on update.
		
		$extension_paths = array();
		$handle = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
		if ( $handle ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
					if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/core.php' ) ) {
						$extension_paths[] = WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/';
					}
				}
			}
		}
		echo '<pre>';
		var_dump( $extension_paths );
		echo '</pre>';
		
		// Put together a list of plugin extensions
		$plugin_extensions = apply_filters( 'bebop_plugin_extensions', array() );
		if ( ! empty( $plugin_extensions ) ) {
			$extension_paths = array_merge( $extension_paths, $plugin_extensions );
		}
			
		echo '<pre>';
		var_dump( $extension_paths );
		echo '</pre>';
		return $extension_paths;
	}
	
	function bebop_load_extensions() {
		$handle = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
		if ( $handle ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
					if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/core.php' ) ) {
						include( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/core.php' );
					}
				}
			}
		}
	}
	
	function bebop_get_extension_configs() {
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
	function bebop_get_extension_config_by_name( $extension ) {
		if ( bebop_extensions::bebop_extension_exists( $extension ) ) {
			if ( ! function_exists( 'get_' . $extension . '_config' ) ) {
				require( WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/config.php' );
			}
			return call_user_func( 'get_' . $extension . '_config' );
		}
		else {
			return false;
		}
	}
	function bebop_get_active_extension_names( $addslashes = false ) {
		//only pull data form active extensions
		$handle     = opendir( WP_PLUGIN_DIR . '/bebop/extensions' );
		$extensions = array();
		//loop extentions so we can add active extentions to the import loop
		if ( $handle ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				if ( $file != '.' && $file != '..' && $file != '.DS_Store' ) {
					if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $file . '/import.php' ) ) {
						if ( bebop_tables::get_option_value( 'bebop_' . $file . '_provider' ) == 'on' ) {
							if ( $addslashes == true ) {
								$extensions[] = "'" . $file . "'";
							}
							else {
								$extensions[] = $file;
							}
						}
					}
				}
			}
		}
		return $extensions; 
	}
	
	function bebop_extension_exists( $extensions ) {
		if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . strtolower( $extensions ) . '/core.php' ) ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	
	function bebop_page_loader( $extension ) {
		$extension = strtolower( $extension );
		if ( file_exists( WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/config.php' ) ) {
			if ( ! function_exists( 'get_' . $extension . '_config' ) ) {
				require( WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/config.php' );
			}
			$config = call_user_func( 'get_' . $extension . '_config' );
			
			if ( ! isset( $_GET['settings'] ) ) {
			 $page = strtolower( $config['defaultpage'] );
			}
			else {
				$page = strtolower( $_GET['settings'] );
			}
			
			if ( ! empty( $_GET['child'] ) ) {
				$extension = $_GET['child'];
			}
			include WP_PLUGIN_DIR . '/bebop/extensions/' . $extension . '/templates/admin-settings.php';
		}
		else {
			echo '<div class="bebop_error_box"><b>Bebop Error:</b> "' . $extension . '" is not a valid extension.</div>';
			include_once( WP_PLUGIN_DIR . '/bebop/core/templates/admin/bebop-admin-menu.php' );
		}
	}
	
	function bebop_user_page_loader( $extension, $page = 'settings' ) {
		global $bp;
		if ( $bp->displayed_user->id != $bp->loggedin_user->id && $page != 'album' ) {
			header( 'location:' . get_site_url() );
		}
		add_action( 'wp_enqueue_scripts', 'bebop_user_stylesheets' );
		add_action( 'bp_template_content', 'bebop_user_'.$page.'_screen_content' );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}
}