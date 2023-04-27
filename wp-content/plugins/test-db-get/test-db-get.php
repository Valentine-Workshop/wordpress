<?php
/*
Plugin Name: Test db get
Plugin URI: http://example.com/test-db-get
Description: A simple plugin to retrieve database table information
Version: 1.0
Author: Valentine Chong
Author URI: http://example.com
License: GPL2
*/

function test_db_get_shortcode()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'usermeta';
    $table_data = $wpdb->get_results("SELECT * FROM $table_name WHERE meta_key LIKE 'user_registration_date_box_%'");
    $count = 0;
    $today = date('d M Y');
    echo "Today birthday's $today<br>";
    foreach ($table_data as $data) {
        $date = $data->meta_value;
        if (date('Y', strtotime($date)) != $today) continue;
        $count++;
        echo "$count. ";
        echo date('d M Y', strtotime($date)) . " (uid #{$data->user_id})<br>";
    }
    echo "<br><br>$count user's birthday today";
}
add_shortcode('test_db_get', 'test_db_get_shortcode');


function test_form_shortcode()
{
    echo 'in';
}
add_shortcode('test_form', 'test_form_shortcode');
