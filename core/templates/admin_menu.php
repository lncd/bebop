<div class="bebop_admin_menu">
    <a id="bebop_admin" href="?page=bebop_admin" <?php if( ( ! isset( $_GET['settings'] ) ) && ( ! isset( $_GET['view'] ) ) ) { echo 'class="activetab"'; }?>>Admin Home</a>
    <a id="bebop_oer_providers" href="?page=bebop_admin&settings=oer_providers" <?php if( isset( $_GET['settings'] ) && $_GET['settings'] == "oer_providers" ) { echo 'class="activetab"'; }?>>OER Providers</a>     
    <a id="bebop_general" href="?page=bebop_admin&settings=general" <?php if( isset( $_GET['settings'] ) && $_GET['settings'] == "general" ) { echo 'class="activetab"'; }?>>General Settings</a>     
    <a id="bebop_cron" href="?page=bebop_admin&settings=cron" <?php if( isset( $_GET['settings'] ) && $_GET['settings'] == "cron" ){ echo 'class="activetab"'; }?>>Cron</a>     
    <a id="bebop_error_log" href="?page=bebop_admin&view=error_log" <?php if( isset( $_GET['view'] ) && $_GET['view'] == "error_log" ){ echo 'class="activetab"'; }?>>Error Log</a>
    <a id="bebop_general_log" href="?page=bebop_admin&view=general_log" <?php if( isset( $_GET['view'] ) && $_GET['view'] == "general_log" ){ echo 'class="activetab"'; }?>>General Log</a>     
</div>