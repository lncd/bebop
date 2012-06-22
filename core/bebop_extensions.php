<?php

class bebop_extensions {
	
	function page_loader($extention){

        $config = parse_ini_file( WP_PLUGIN_DIR."/bebop/extentions/".$extention."/config.ini" );

        if( ! isset($_GET["settings"])){ 
            $page = ucfirst($config['defaultpage']);
        }
        else {
            $page = ucfirst($_GET["settings"]);
        }

        if( $_GET['child'] ) {
            $extention = $_GET['child'];
        }
        include WP_PLUGIN_DIR."/bebop/extentions/".$extention."/templates/Admin".$page.".php";
    }
	
}