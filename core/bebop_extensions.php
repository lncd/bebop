<?php

class bebop_extensions {
	
	function page_loader($extention){

        $config = parse_ini_file( WP_PLUGIN_DIR."/bebop/extentions/".$extention."/config.ini" );

        if( ! isset($_GET["settings"])){ 
            $page = strtolower($config['defaultpage']);
        }
        else {
            $page = strtolower($_GET["settings"]);
        }

        if( $_GET['child'] ) {
            $extention = $_GET['child'];
        }
        include WP_PLUGIN_DIR."/bebop/extentions/".$extention."/templates/admin_ ".$page.".php";
    }
	
	function get_extension_configs() {

        $config = array();
        $handle = opendir(WP_PLUGIN_DIR . "/bebop/extentions");
        
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".DS_Store") {
                    if (file_exists(WP_PLUGIN_DIR."/bebop/extentions/" . $file . "/config.ini")) {
                        $config[] = parse_ini_file(WP_PLUGIN_DIR."/bebop/extentions/" . $file . "/config.ini");
                    }
                }
            }
        }
		var_dump($config);
        return $config;
    }
    
	function extension_exist($name){
        if (file_exists(WP_PLUGIN_DIR."/bebop/extentions/" . strtolower($name) . "/core.php")) {
            return true;
        }
        return false;
    }
}