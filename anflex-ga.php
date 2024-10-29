<?php

/*
Plugin Name: Anflex GA - Google Analytics for Wordpress
Plugin URI: http://anflex.net/ga-wordpress/
Description: Anflex GA enbeds Google Analytics for Wordpress websites, provideing easy-setup external/download link tracking using Event Tracking method.
Author URI: http://anflex.net/
Version: 1.1.1
Author: Anflex
*/

$anflex_ga_ver="1.1.1";

/*  Copyright 2010 Yuichiro Wada (email : yuichirowada@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Version History

	1.1.1
	- Fixed minor admin interface bug
	
	1.1
	- Change Google Analytics Code to Async Snippet

	1.0.4 - 2010/03/17
	- Fixed Download Link tracking bug introduced in 1.0.3
	
	1.0.3 - 2010/03/17
	Fixed External Link tracking bug: hrefs that does not start with http: or https: won't be counted as External Link
	
	1.0.2 - 2010/03/12
	- Improved performance
	- Download link now kills External Link if both case matches

	1.0.1 - 2010/03/11
	- Minor Bugfixes
	- Admin description fix and updates

	1.0 - 2010/03/08
	- Initial Release
	- Admin exclusion
	- Link Tracking (External and Download)

*/

require_once(WP_PLUGIN_DIR.'/anflex-ga/php/utilities.php');
require_once(WP_PLUGIN_DIR.'/anflex-ga/php/anflex_ga.php');

/* initiate anflex_ga instance */
if (class_exists("anflex_ga")) {
	$anflex_ga = new anflex_ga();
	anflex_msg('new anflex_ga instance is created');
}
  
if (isset($anflex_ga)) {
	
	$anflex_ga_ss = $anflex_ga->gs();

	// add_action('activate_anflex-ga/anflex_ga.php',array(&$anflex_ga, 'gs'));
	add_action('admin_menu',array(&$anflex_ga,'aap'));
	add_action('init',array(&$anflex_ga,'load_jquery'));
	
	if(is_admin()&&$_GET["page"]=='anflex_ga.php') {
		anflex_msg('this is Anflex GA admin page');
		add_action('init',array(&$anflex_ga,'load_admin_scripts'));
	}

	if(!is_admin()&&($anflex_ga_ss['lt_d']==true||$anflex_ga_ss['lt_e'] == true||$anflex_ga_ss['link_tracking_download']==true||$anflex_ga_ss['link_tracking_external'] == true)) {
		anflex_msg('load_event_scripts is added to init');
		add_action('init',array(&$anflex_ga,'load_event_scripts'));
	}

	if(($anflex_ga_ss['t'] == true||$anflex_ga_ss['tracking']==true)&&$anflex_ga_ss['gaid'] != $anflex_ga->d_gaid) {
		anflex_msg('aac is added to wp_footer');
		add_action('wp_footer',array(&$anflex_ga, 'aac'));
	}

	if($anflex_debug==true) {
		anflex_msg('anflex_pmsg is added to wp_footer');
		add_action('wp_footer','anflex_pmsg');
	}

}

?>