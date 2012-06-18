<div class="bebop_admin_menu">
    <a id="bebop_admin" href="?page=bebop_admin" <?php if(!isset($_GET['settings'])){ echo 'class="activetab"'; }?>>Admin Home</a>
    <a id="bebop_apps" href="?page=bebop_admin&settings=apps" <?php if(isset($_GET['settings']) && $_GET['settings']=="bebop_apps"){ echo 'class="activetab"'; }?>>Apps</a>     
    <a id="bebop_general" href="?page=bebop_admin&settings=general" <?php if(isset($_GET['settings']) && $_GET['settings']=="general"){ echo 'class="activetab"'; }?>>General Settings</a>     
    <a id="bebop_cron" href="?page=bebop_admin&settings=cron" <?php if(isset($_GET['settings']) && $_GET['settings']=="cron"){ echo 'class="activetab"'; }?>>Cron</a>     
    <a id="bebop_error_log" href="?page=bebop_admin&view=error_log" <?php if(isset($_GET['view']) && $_GET['view']=="error_log"){ echo 'class="activetab"'; }?>>Error Log</a>
    <a id="bebop_general_log" href="?page=bebop_admin&view=general_log" <?php if(isset($_GET['view']) && $_GET['view']=="general_log"){ echo 'class="activetab"'; }?>>General Log</a>     
    <a href="#" class="tab_v2">V<?php echo BP_BEBOP_VERSION;?></a>     
</div>