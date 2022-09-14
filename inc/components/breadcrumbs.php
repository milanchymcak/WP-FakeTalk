<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');

// $FakeTalk_structure must be set with the submenu_page to get the plugin menu structure
$FakeTalk_structure = include (FAKETALK_PATH . 'config.php');

$breadCrumbs = '<div class="faketalk_breadcrumbs">';
$breadCrumbs .= '<ul>';

// Add home link
$breadCrumbs .= '<li>';
$breadCrumbs .= '<a href="' . get_bloginfo('url') . '/wp-admin/admin.php?page=' . $FakeTalk_structure['config']['pluginSlug'] . '-dashboard" rel="noopener noreferrer">Home</a>';
$breadCrumbs .= '</li>';

foreach($FakeTalk_structure['submenu_page'] as $breadCrumb_Page) {

    // Show the specific page in the breadcrumbs
    if($menuPageSlug !== strtolower(str_ireplace(' ', '', $breadCrumb_Page))) continue;

    // Get Breadcrumb URL
    $breadcrumb_URL = get_bloginfo('url') . '/wp-admin/admin.php?page=' . $FakeTalk_structure['config']['pluginSlug'] . '-' . strtolower(str_ireplace(' ', '', $breadCrumb_Page));

    // Add Link To The Breadcrumb
    $breadCrumbs .= '<li>';
    $breadCrumbs .= '<a href="' . $breadcrumb_URL . '" rel="noopener noreferrer">'.$breadCrumb_Page.'</a>';
    $breadCrumbs .= '</li>';

}
$breadCrumbs .= '</ul>';
$breadCrumbs .= '</div>';

return $breadCrumbs;