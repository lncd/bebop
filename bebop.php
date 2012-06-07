<?php
/*
Plugin Name: Bebop
Plugin URI: http://bebop.blogs.lincoln.ac.uk/
Description: Bebop is the name of a rapid innovation project funded by the Joint Information Systems Committee. The project involved the utilisation of OER's from 3rd party providers such as JORUM and slideshare.
Version: 0.1
Author: Dale Mckeown
Author URI: http://phone.online.lincoln.ac.uk/dmckeown
License: TBA
*/

// This plugin is intended for BuddyPress only.
// http://buddypress.org/
//

/*****************************************************************************
** This program is distributed WITHOUT ANY WARRANTY; without even the 		**
** implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. **
*****************************************************************************/


//initialise Bebop
function bebop_init() {
	
	//Define plugin version
	define('BP_BEBOP_VERSION', '0.1');
	//define('BP_BEBOP_IS_INSTALLED', 1);
	
	//init database
	
	//init settings
	
	//include files from core.
	include_once('core/core.php');
}
    
    
?>