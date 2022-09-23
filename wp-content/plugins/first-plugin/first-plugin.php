<?php
/**
* Plugin Name: First Plugin
* Plugin URI: http://test.php/
* Description: The way to develop the first plugin.
* Version: 0.1
* Author: Valentine
* Author URI: http://test.php/
**/

// Actions: These add or change WordPress functionality and make up the majority of hooks. 
// Filters: These are used to modify the functionality of actions.

function modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">Click to Read!</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

add_action('modify_read_more_link');