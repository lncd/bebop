<?php

class bebop_extensions {
	
	function load_extensions(){

        $handle = opendir( WP_PLUGIN_DIR . "/bebop/extensions" );
        if ( $handle ) {
            while ( false !== ( $file = readdir($handle) ) ) {
                if ( $file != "." && $file != ".." && $file != ".DS_Store" ) {
                    if ( file_exists( WP_PLUGIN_DIR."/bebop/extensions/" . $file . "/core.php" ) ) {
                        include( WP_PLUGIN_DIR."/bebop/extensions/" . $file . "/core.php" );
                    }
                }
            }
        }
    }
	
	function page_loader($extensions) {
		
        $config = parse_ini_file( WP_PLUGIN_DIR."/bebop/extensions/".$extensions."/config.ini" );
        if( ! isset($_GET["settings"])){ 
            $page = strtolower($config['defaultpage']);
        }
        else {
            $page = strtolower($_GET["settings"]);
        }

        if( $_GET['child'] ) {
            $extensions = $_GET['child'];
		}
        include WP_PLUGIN_DIR."/bebop/extensions/".$extensions."/templates/admin_".$page.".php";
    }
	
	function get_extension_configs() {

        $config = array();
        $handle = opendir(WP_PLUGIN_DIR . "/bebop/extensions");
        
        if ( $handle ) {
            while ( false !== ( $file = readdir( $handle ) ) ) {
                if ( $file != "." && $file != ".." && $file != ".DS_Store" ) {
                    if ( file_exists( WP_PLUGIN_DIR."/bebop/extensions/" . $file . "/config.ini" ) ) {
                        $config[] = parse_ini_file( WP_PLUGIN_DIR."/bebop/extensions/" . $file . "/config.ini" );
                    }
                }
            }
        }
        return $config;
    }
	
	function extension_exist($extensions) {
		
        if ( file_exists( WP_PLUGIN_DIR."/bebop/extensions/" . strtolower($extensions) . "/core.php" ) ) {
            return true;
        }
		else {
        	return false;
		}
    }
}