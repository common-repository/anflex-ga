=== Plugin Name ===
Contributors: Anflex
Tags: analytics, google analytics, google, stats, statistics, web analytics
Requires at least: 2.7
Tested up to: 2.9.2
Stable tag: 1.1.1

Anflex GA quickly adds Google Analytics tracker code to Wordpress websites, providing admin exclusion, external/download link tracking using Event Tracking.

Since Version 1.1, tracker code has been updated to the latest Asynchronous tracking code.  This implementation provides faster load time and site speed compared to traditional tracking code.

== Description ==

Anflex GA quickly adds Google Analytics code to Wordpress-based websites.

*	Administrator can be excluded from being tracked.
*	External Link and Download Link tracking can be enabled (using Event Tracking)

Screenshots and helps in detail please visit [http://anflex.net/ga-wordpress/].

[http://anflex.net/ga-wordpress/]: http://anflex.net/ga-wordpress/


== Installation ==

1. Download plugin and unzip the file
1. Upload `anflex-ga` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Look for `Anflex GA` menu under `Settings` left-side menu for initial setup

== Changelog ==


= 1.1.1 - 2010/04/25 =
* Fixed minor admin interface bug

= 1.1 - 2010/04/03 =
* Change Google Analytics Code to Async Snippet

= 1.0.4 - 2010/03/17 =
* Fixed Download Link tracking bug introduced in 1.0.3

= 1.0.3 - 2010/03/17 =
* Fixed External Link tracking bug: hrefs that does not start with http: or https: won't be counted as External Link

= 1.0.2 - 2010/03/12 =
* Improved performance
* Simplified Admin Panel
* Download link now kills External Link if both matches at the same time

= 1.0.1 2010/03/11 =

* Minor Bugfix
* Admin description changes

= 1.0 2010/03/08 =

* Initial Release
* Admin exclusion
* Link Tracking (External and Download)