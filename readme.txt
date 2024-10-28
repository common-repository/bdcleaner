=== BD cleaner ===
Contributors: hackeamesta
Author: hackeamesta
Tags: optimize, clean, data base, SEO, Backup, Restore, database manager, MySQl
Requires at least: 1.0
Tested up to: 1.2
Stable tag: trunk
Donate link: http://xora.org/bdcleaner.php
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Explore, optimize, analyze and clean your database from plugins and custom searches

== Description ==

BDcleaner allows explore, optimize, analyze and clean your database from plugins and custom searches

= Plugins Support =
* All In One Seo Plugin
* Wordpress SEO by Yoast
* Disqus comments System
* Akismet
* BBpress
* BuddyPress
* Installed Plugins
* Custom parameter / Word

= Characteristics =
BDcleaner implements a generic connection based on your configuration file (wp-config.php) to remove $wpdb restrictions.

* Support backup / Restore database (Beta).
* Find records in database of installed plugins.
* Support for MultiDatabase.
* Optimize Database ( Posts, Tags, feed cache, pingbacks, user agents, comments ).

== Installation ==
* Make backup all your database / files
* Unpack the downloaded package
* Upload files to the wp-content/plugins/bdcleaner
* Set 0777 permissions at <strong>/wp-content/plugins/bdcleaner/backups</strong>

= Requirements =
* PHP 5
* MySQL 5

== Screenshots ==
1. Optimize Database
2. Query used
3. Database and tables
4. Custom Word / Parameter
5. Results


== Credits ==
www.xora.org | www.rootprojects.com | @xoraorg | @HackeaMesta | @eldragon87 |
contacto@xora.org

== Changelog ==
= 2.0 =
* New Feature: Added support for backup / restore database.
* New Feature: Find records in database of installed plugins.
* New Feature: Custom AIO SEO parameters ( Title, Keywords, Description ).
* New Feature: Custom SEO by YOAST parameters ( Meta Description, Meta Robots, Sitemap, Canonical, Redirect, Focus ).
* New Feature: Support Español / English.
* Fixed bug: Encoding results error (Hybrid system $wpdb / generic).

= 1.2 =
* New Feature: Added support for MultiDatabase
* Fixed bug: Permissions required

= 1.0 =
* Initial Release