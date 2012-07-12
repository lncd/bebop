<?php if( ! isset($_GET['oer']) ) {?>
      <div>
      <ul>
 
          <?php 
          //Only shows if it is the users profile. 
          if(bp_is_my_profile())
            {
                  echo "<h3>User Settings</h3>";
                 
            //get the active extension
            foreach( bebop_extensions::get_extension_configs() as $extension ) {
                  if(bebop_tables::get_option_value('bebop_'.$extension['name'].'_provider') == "on") {    
                  $activeExtensions[] = $extension['name'];
                  }
            }
                  if( count($activeExtensions) == 0 ) {
                        echo "No extensions are currently active. Please activate them in the bebop OER providers admin panel.";
                  }
                  else {
                        echo "Choose an OER source from the sub menu above. ";
                  }
            }
           
 
 
 
      var_dump( bp_ajax_querystring( 'activity' ));
      //This is being modified atm
 
        ?>
        <div id="content">
        <div class="item-list-tabs no-ajax" id="subnav" role="navigation">
                        <ul class="clearfix">
                              <li id="activity-filter-select" class="last">
                                    <label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label>
                                    <select id="activity-filter-by">
                                          <option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>
                                         
                                          <?php do_action( 'bp_activity_filter_options' ); ?>
 
                                    </select>
                              </li>
                        </ul>
                  </div><!-- .item-list-tabs -->
        <div class="activity" role="main">
        <?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>
 
      <?php /* Show pagination if JS is not enabled, since the "Load More" link will do nothing */ ?>
      <noscript>
            <div class="pagination">
                  <div class="pag-count"><?php bp_activity_pagination_count(); ?></div>
                  <div class="pagination-links"><?php bp_activity_pagination_links(); ?></div>
            </div>
      </noscript>
 
      <?php if ( empty( $_POST['page'] ) ) : ?>
      <div id="content">
 
            <ul id="activity-stream" class="activity-list item-list">
 
      <?php endif; ?>
 
      <?php while ( bp_activities() ) : bp_the_activity(); ?>
 
            <?php locate_template( array( 'activity/entry.php' ), true, false ); ?>
 
      <?php endwhile; ?>
     
      <?php if ( empty( $_POST['page'] ) ) : ?>
 
            </ul>
                  </div>
 
 
      <?php endif; ?>
 
<?php else : ?>
 
      <div id="message" class="info">
            <p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
      </div>
 
<?php endif; ?>
 
 
</div>
    </ul>
</div>
</div>
 
<?php
}
else {
      include(WP_PLUGIN_DIR."/bebop/extensions/" . $_GET['oer'] . "/templates/user_settings.php");
}
?>