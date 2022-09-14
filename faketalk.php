<?php
/**
 * Plugin Name:       FakeTalk - WP Fake Comments Generator
 * Plugin URI:        https://chymcakmilan.com
 * Description:       Generate more comments for your blog posts with just one click!
 * Version:           0.1
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * Author:            Milan Chymcak
 * Author URI:        https://chymcakmilan.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://chymcakmilan.com/plugin
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Protect all files from direct accessing them by outsiders
if (!defined('ABSPATH')) die('Boo! Do not access the file directly!'); 

/**
 * Plugin Constants
 *
 */
define('FAKETALK_LOGGED', '1');
define('FAKETALK_VERSION', '0.1');
define('FAKETALK_PATH', realpath(plugin_dir_path( __FILE__ )) . DIRECTORY_SEPARATOR);
define('FAKETALK_URL', plugin_dir_url( __FILE__ ));

/**
 * Autoload Plugin Classes
 *
 */
spl_autoload_register(function($className) {

	// Must contains our namespace
	if(str_contains($className, 'FakeTalk')) {

		// get just class name
		$className = str_replace('FakeTalk', '', $className);
		$className = str_replace('\\', '', $className);

		include(FAKETALK_PATH . 'classes/' . $className . '.php');
	}
});

/**
 * Uninstall Plugin Hook
 * Delete All Settings Related To The Plugin When Uninstall
 * 
 *
 */
register_uninstall_hook(__FILE__, 'faketalk_delete_settings');

/**
 * faketalk_delete_settings
 * Also used in the submitData.php when user decide to delete all settings
 *
 * @return void
 */
function faketalk_delete_settings(): void {
	// Changing TLD
	delete_option('_faketalk_option_tld');

	// Changing Max Posts
	delete_option('_faketalk_option_max_amount');

	// Changing Max Posts - Query
	delete_option('_faketalk_option_max_amount_query');

	// Changing Approved By Default
	delete_option('_faketalk_option_approved');
}

/**
 * Main Structure Of The Plugin
 * Configs, Menu Hierarchy
 */
$FakeTalk_structure = include (FAKETALK_PATH . 'config.php');

/**
 * Init the Plugin
 * Create the menu with submenu content in the WP-Admin Sidebar
 */
new FakeTalk\menuContent($FakeTalk_structure);

/*
Copyright 2022 Milan Chymcak

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.
*/
?>
