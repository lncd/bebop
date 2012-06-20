<link rel="stylesheet" href="<?php echo plugins_url() . '/bebop/core/resources/css/admin.css';?>" type="text/css"> 

<?php include_once( 'admin_menu.php' ); ?>
<div id='bebop_admin_container'>
	<div class="bebop_admin_box">
	    <img class="bebop_logo" src="<?php echo plugins_url() . '/bebop/core/resources/images/bebop_logo.png';?>"><br>
	    Welcome to the OER plugin for BuddyPress. Developed by <a href='http://www.lncd.lincoln.ac.uk'>LNCD @ the University of Lincoln</a>.
	    <div class="clear"></div>
	</div>
	
	<div class="postbox-container">
		
        <div class="meta-box-sortables ui-sortable">
            <div class="postbox">
            	<h3>Latest News</h3>
            	<div class="inside" style="padding:10px;">
                    Bebop News       
                </div>
            </div>
            
            <div class="postbox">
                <h3>Container</h3>
            	<div class="inside" style="padding:10px;">
                    Container     
                </div>
               
            </div>
		<!-- End normal-sortables -->
        </div>
   	<!-- End postbox-container -->
    </div>
    
    <div class="postbox-container">
		
        <div class="meta-box-sortables ui-sortable">
    
    		<div class="postbox">
		        <h3>Support</h3>
	            <div class="inside" style="padding:10px;">
	                Support Description         
	            </div>
		    </div>
		</div>
	</div>
	
	<div class="clear"></div>
</div>
<!-- end bebop_admin_container -->