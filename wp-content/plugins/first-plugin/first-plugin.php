<?php

/**
 * Plugin Name: First Plugin
 * Plugin URI: http://test.php/
 * Description: The way to develop the first plugin.
 * Version: 0.1
 * Author: Valentine
 * Author URI: http://test.php/
 * 
 **/

// Actions: These add or change WordPress functionality and make up the majority of hooks. 
// Filters: These are used to modify the functionality of actions.


// function modify_read_more_link() {
//     return '<a class="more-link" href="' . get_permalink() . '">Click to Read!</a>';
// }
// add_filter( 'the_content_more_link', 'modify_read_more_link' );

// function check_plugin_state(){
//     if (is_plugin_active('first-plugin/first-plugin.php')){
//      echo 'plugin is active';
//    }else{
//     echo 'plugin is not active';
//    }
// }
// add_action('modify_read_more_link');

// Add sidebar modules
add_action('admin_menu', 'pluginSidebarModules');
function pluginSidebarModules()
{
    add_menu_page('First Sidebar Modules', 'First Sidebar Modules', 'manage_options', 'first-sidebar-plugin', 'modulePageHTML');
}
// Sidebar HTML
function modulePageHTML()
{
?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="wrap">
        <h1 class="wp-heading-inline">First Sidebar Modules</h1>
        <a href="#" onclick="alert()" class="page-title-action">Add New</a>
    </div>

    <script>
        function alert(){
            swal.fire('Clicked','You have clicked Add New Button on top','success');
        }
    </script>
<?php
}
