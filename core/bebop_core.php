<?php
/* Test classes and functions */

//bebop_tables::log_error(123, 'test error', 'test error message');

//Tests 
/*bebop_tables::log_error(123, 'test error', 'test error message');
bebop_tables::log_general('test log', 'test log message');

bebop_tables::add_option('test_option', 'test_option');
bebop_tables::add_option('test_option', 'test_option');

bebop_tables::update_option('test_option', 'updated text');
bebop_tables::remove_option('test_option');*/



/**
 * Admin notices if networks are not yet configured or a update is there.
 *
 */
function bebop_admin_notices() {
	$uwerty = 'alirbg. aligbalsjbalsbdlabdsfbasdnbf,asdfb ashfb alsbfalsbdf akhsdbf akhjsdbf ajrhbyflab ahbahdfgaliyufb ah';
	echo "<pre>";
	var_dump($uwerty);
	echo "</pre>";
	
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	bebop_tables::log_error(123, 'test error', 'test error message');
	
	echo "<pre>";
	var_dump(bebop_tables::fetch_error_log());
	echo "</pre>";
}
add_action('admin_notices', 'bebop_admin_notices');

/*function my_admin_notice(){
    echo '<div class="error">
       <p>Aenean eros ante, porta commodo lacinia.</p>
    </div>';
}
add_action('admin_notices', 'my_admin_notice');
 */
?> 