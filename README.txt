=== Bebop ===
Contributors: Dale Mckeown
Tags: WordPress, BuddyPress, OER, Open Educational Resources, Rapid Innovation, JISC, LNCD.
Tested on: WordPress 3.4.2, BuddyPress 1.6.1


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
3. Configure extensions in the 'OER Provider' admin menu.

== Changelog ==

v1.1.1 - 29.09.2012 - Minor Release

This is a minor update which fixes some bugs found in the previous version.

Feature Updates:
N/A

Bug Fixes:
1. Fixed a bug which was overriding the custom bebop activity feed query when first loaded, which caused all content to be displayed, not just Bebop generated content.
2. Fixed an issue with updating the main wordpress cron time.




Other:
1. Fixed an issue with with strange log messages of no consumer key is set for an extension.

v1.1 - 24.09.2012 - Major Release

This version changes some fundemental aspects of the plugin source code. See features for details.

Feature Updates:
1. When a new account is added to an extension, an initial import is fired to allow content to become available in the unverified resources without having to wait for the cron import to do its magic.
2. Introduced a secondary cron which scans a new batabase table every 15 seconds to detect feeds which are due an initial import. This means the default cron can be ran less often, while new users/feeds can still import content.
3. Changed default cron time to 10 minutes to support the above feature.
4. Updated all extension import scripts to allow processing of the new cron.
5. Changed the logo for the plugin.
6. Improved RSS atom compatability.
7. Added some hooks in relevant places to allow further extensions to be developed and easily hooked into bebop's functionality.
8. Moved RSS links to top of the Teaching Resources home tab, to prevent repetition and to make feeds visible even with no-one logged in.
9. Made it easier for extensions to hook into bebop.
10. Seperated admin functions from normal functions to increase performance.
11. Placed buttons in the admin general settings which can automatically fire a cron. This could aid problem solving with users if they cannet get content to import.
12. Added another general settings option which allows content to be either verified automatically, or be verified by the user through the OER manager.


Bug Fixes:
1. Removed importer queue as it was no longer required.
2. Fixed a rather complexed quote escaping problem in user feeds.
3. Fixed a bug which displayed the user's activity stream if no OER extensions are active.
4. Fixed an issue which forced the RSS feeds generator to always output results for 'all_oers' regardless of what content supposed to display.
5. Prefixed some functions to prevent some possible name conflicts with other possible plugins.
6. Fixed a Flickr import bug which accidentally removed data from the OER manager table.
7. Fixed a bug which made it difficult to remove a users feed from a regular extension.
8. Fixed a bug which threw a fatal error if BuddyPress is not active when Bebop is activated.
9. Fixed a bug which overwrote standard  WordPress CSS rules.


Other:
1. Made a few import error/log messages a bit more clear.


v1.0.1 - 11.09.2012 - minor release.

This version adds a couple of extra features, but more importantly fixes a major bug regarding import limits and importing items.

Feature Updates:
1. RSS feeds are not shown for bebop content in the activity streams. The extension RSS feed for the user is shown, and the "all OER" feed is shown if more than one feed is active.
2. The Twitter extension has been updated to conform with the new Twitter API.
3. The admin interface has been replaced by standard WordPress and BuddyPress markup, resulting in a more "WordPressy" feel.
4. The user interface has been replaced by standard WordPress and BuddyPress markup, resulting in a more "WordPressy" feel.
5. Import scripts that require an API key now log to the general log instead of the error log incase a API key is not found. Changed because it is not specifically a Bebop error, but a user error.
6. Changed admin settings link in OER providers to a button.
7. Changed 'Generic RSS' extension to 'Feed' Extension.


Bug Fixes:
1. A bug was fixed which made the OER filter tab dislay the wrong text.
2. RSS feeds are closer to validating properly.
3. Fixed deactivation bug where "bebop_tables()" class is not found.
4. Fixed import twitter bug where no username is specified.
5. Fixed error log not displaying properly.
6. Fixed a user settings bug where edit options were available but no API key is set in the admin panel.
7. Fixed bug where the same content is loaded when the 'load more' button is clicked.
8. Fixed a bug where the OER filter is displaying extra content filters from other plugins.
9. Fixed a bug that stopped OER content from being imported if no import limit was set for an extension.
10. Fixed a bug where admin error messages were showing the "settings saved" when they should return an error.
11. Fixes a bug where additional filters from other plugins were being added to the OER filters.
12. Fixed bug where it is not possible to reset a deleted OER.


Other:
1. Support email changed to Github wiki.
2. Removed the "break;" from import scripts - to retrieve older data if an import limit is removed.
3. Changed some terminology here and there.

v1.0 - 30.08.2012 - Initial release.