<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css">

<?php include_once( 'admin_menu.php' ); ?>

<div class="buddystream_info_box_dashboard">
    <img src="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_icon.png';?>" width="100" align="left" style="padding-right:15px; padding-bottom:15px;"><br>
    Welcome to the bebop plugin.
</div>

<div id="dashboard-widgets-wrap">
	<div class="metabox-holder" id="dashboard-widgets">
		<div style="width: 49%;" class="postbox-container">
	        <div class="meta-box-sortables ui-sortable" id="normal-sortables">
	
	          
	            <div class="postbox">
	                <div><h3 style="cursor:default;"><span>Latest News</span></h3>
	                    <div class="inside">
	                        <ul class="buddystream_news">
	                        </ul>
	                    </div>
	                </div>
	            </div>
	            
	             <div class="postbox">
	                <div><h3 style="cursor:default;">SUPPORT</h3>
	                    <div class="inside" style="padding:10px;">
	                        support Description         
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	
	    <div style="width: 49%;" class="postbox-container">
	        <div class="meta-box-sortables ui-sortable" id="normal-sortables">
	
	            <div class="postbox">
	                <div><h3 style="cursor:default;"><span>Container</span></h3>
	                </div>
	            </div>
	        </div>
	    </div>
	    <!-- End postbox-container -->
	</div>
	
<div class="clear"></div>
</div>