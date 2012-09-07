=== Bebop ===
Contributors: Dale Mckeown
Tags: WordPress, BuddyPress, OER, Open Educational Resources, Rapid Innovation, JISC, LNCD.
Tested on: WordPress 3.4.1, BuddyPress 1.6.1


== Licence ==
Released under the GNU General Public Licence - https://www.gnu.org/copyleft/gpl.html
Copyright 2012 The University of Lincoln - http://www.lincoln.ac.uk.


== Description ==
Bebop is the name of a rapid innovation project funded by the Joint Information Systems Committee (JISC) and developed by the University of Lincoln. The project involved the utilisation of OER's from 3rd party providers such as YouTube, Vimeo, SlideShare and Flickr.

Requirements.
- PHP 5.2.1+

== Installation ==
1. Upload this plugin to your '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure extensions in the 'OER Provider' menu.

== Changelog ==

v1.1 - 06.09.2012

Feature Updates:
1. RSS feeds are not shown for bebop content in the activity streams. The extension RSS feed for the user is shown, and the "all OER" feed is shown if more than one feed is active.
2. The Twitter extension has been updated to conform with the new Twitter API.
3. The admin interface has been replaced by standard WordPress and BuddyPress markup, resulting in a more "WordPressy" feel.
4. The user interface has been replaced by standard WordPress and BuddyPress markup, resulting in a more "WordPressy" feel.
5. Import scripts that require an API key now log to the general log instead of the error log incase a API key is not found. Changed because it is not specifically a Bebop error, but a user error.

Bug fixes
1. A bug was fixed which made the OER filter tab dislay the wrong text.
2. RSS feeds now validate.
3. Fixed deactivation bug where bebop_tables class is not found.
4. Fixed import twitter bug where no username is specified.
5. Fixed error log not displaying properly.


Other
1. Support email changed to github wiki.


v1.0 - 30.08.2012 - Initial release.