<?php
/**
 * RSS2 Feed Template for displaying various faceted feeds
 *
 * @package BP Lotsa Feeds
 */
header( 'Cache-Control: no-cache, must-revalidate' ); // HTTP/1.1
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
header('Status: 200 OK');

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	<?php do_action('bp_activity_personal_feed'); ?>
>

<channel>
	<title><?php echo bp_site_name() ?> | <?php bebop_feed_name(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bebop_feed_url() ?></link>
	<description><?php bebop_feed_name() ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s O', bp_activity_get_last_updated(), false); ?></pubDate>
	<generator>http://buddypress.org/?v=<?php echo BP_VERSION ?></generator>
	<language><?php echo get_option('rss_language'); ?></language>
	<?php do_action('bp_activity_personal_comment_feed_head'); ?>
	
	<?php
	
	if ( bp_has_activities( bebop_get_activity_args() ) ) {
		while ( bp_activities() ) : bp_the_activity();
		?>
			<item>
				<guid><?php bp_activity_thread_permalink() ?></guid>
				<title><![CDATA[<?php bp_activity_feed_item_title() ?>]]></title>
				<link><?php echo bp_activity_thread_permalink() ?></link>
				<pubDate><?php echo mysql2date('D, d M Y H:i:s O', bp_get_activity_feed_item_date(), false); ?></pubDate>
				<description>
					<![CDATA[
					<?php bp_activity_feed_item_description();
					
					if ( bp_activity_can_comment() ) { ?>
						<p><?php printf( __( 'Comments: %s', 'buddypress' ), bp_activity_get_comment_count() ); ?></p>
					<?php 
					}
					if ( 'activity_comment' == bp_get_activity_action_name() ) { ?>
						<br /><strong><?php _e( 'In reply to', 'buddypress' ) ?></strong> -
						<?php bp_activity_parent_content(); ?>
					<?php 
					}
					?>
					]]>
				</description>
				<?php do_action('bp_activity_personal_feed_item'); ?>
			</item>
		<?php
		endwhile;
	}
	?>
</channel>
</rss>