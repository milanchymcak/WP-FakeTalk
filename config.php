<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');

/**
 * Main Structure Of The Plugin
 * Configs, Menu Hierarchy
 */
return array(
	'config' => array(
		'pluginSlug' => 'wp-fake-comments-generator',
		'pluginTitle' => 'WP Fake Comments Generator'
	),
	'menu_page' => array(
		'title' => 'FakeTalk', // Menu Title
		'icon' => 'dashicons-tickets', // Dashicon for the menu - @link https://developer.wordpress.org/resource/dashicons/#tickets
		'position' => 25 // Position in the sidebar menu of WP Admin
	),
	'submenu_page' => array(
		'Dashboard',
		'Add New',
		'Settings',
		'Statistics',
		'API',
		'About'
	)
);