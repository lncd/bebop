<div class='bebop_admin_menu'>
	<a id='bebop_admin' href='?page=bebop_admin' <?php if ( $_GET['page'] == 'bebop_admin' ) {
		echo 'class="activetab"';
	}?>>Admin Home</a>
	<a id='bebop_general' href='?page=bebop_admin_settings' <?php if ( $_GET['page'] == 'bebop_admin_settings' ) {
		echo 'class="activetab"';
	}?>>General Settings</a>
	<a id='bebop_oer_providers' href='?page=bebop_oer_providers' <?php if ( $_GET['page'] == 'bebop_oer_providers' ) {
		echo 'class="activetab"';
	}?>>OER Providers</a>
	<a id='bebop_oer_s' href='?page=bebop_oers' <?php if ( $_GET['page'] == 'bebop_oers' ) {
		echo 'class="activetab"';
	}?>>OER Providers</a>
	<a id='bebop_error_log' href='?page=bebop_error_log' <?php if ( $_GET['page'] == 'bebop_error_log' ) {
		echo 'class="activetab"';
	}?>>Error Log</a>
	<a id='bebop_general_log' href='?page=bebop_general_log' <?php if ( $_GET['page'] == 'bebop_general_log' ) {
		echo 'class="activetab"';
	}?>>General Log</a>
</div>