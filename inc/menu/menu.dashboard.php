<?php
// Do not access the file directly!
defined('FAKETALK_LOGGED') or die('Boo! Do not access the file directly!');

/**
 * Statistics Box
 */
if(file_exists(FAKETALK_PATH . '/inc/components/statistics.php')) {

    echo '<div class="faketalk_dash_box">';
    echo '<h4>Statistics</h4>';
    include(FAKETALK_PATH . '/inc/components/statistics.php');
    echo '</div>';
}

/**
 * Latest Comments Box
 */
if(file_exists(FAKETALK_PATH . '/inc/components/latest.php')) {

    echo '<div class="faketalk_dash_box">';
    echo '<h4>Latest Generated Comments</h4>';
    echo include(FAKETALK_PATH . '/inc/components/latest.php');
    echo '</div>';
}

/**
 * Important Buttons Box
 */
if(file_exists(FAKETALK_PATH . '/inc/components/buttons.php')) {

    echo '<div class="faketalk_dash_box">';
    echo '<h4>Important Actions</h4>';
    echo include(FAKETALK_PATH . '/inc/components/buttons.php');
    echo '</div>';
}