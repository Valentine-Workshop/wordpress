<?php

/**
 * Plugin Name: Custom Admin Plugin
 * Description: This plugin filters wp_usermeta table by date input.
 * Version: 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
// Global variable for $wpdb.
global $wpdb;

// Plugin activation hook.
register_activation_hook(__FILE__, 'custom_admin_plugin_activate');
function custom_admin_plugin_activate()
{
    // Add code to execute when plugin is activated.
}

// Plugin deactivation hook.
register_deactivation_hook(__FILE__, 'custom_admin_plugin_deactivate');
function custom_admin_plugin_deactivate()
{
    // Add code to execute when plugin is deactivated.
}

// Add menu page.
add_action('admin_menu', 'custom_admin_plugin_menu_page');
function custom_admin_plugin_menu_page()
{
    add_menu_page(
        'Custom Admin Plugin',
        'Custom Admin Plugin',
        'manage_options',
        'custom-admin-plugin',
        'custom_admin_plugin_render_menu_page'
    );
    add_menu_page(
        'Custom Admin Plugin2',
        'Custom Admin Plugin2',
        'manage_options',
        'custom-admin-plugin2',
        'get_user_data_details'
    );
}

// Get user input.
$now = date('Y-m-d', time());
$isToday = isset($_POST['action']) && ($_POST['action'] == 'Today') ? true : false;
$start_date = $isToday ? $now : (isset($_POST['start_date']) ? $_POST['start_date'] : $now);
$end_date   = $isToday ? $now : (isset($_POST['end_date']) ? $_POST['end_date'] : $now);

// Build query.
$query = $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}usermeta WHERE meta_key LIKE 'user_registration_date_box_%' AND meta_value BETWEEN %s AND %s",
    $start_date,
    $end_date
);
// Execute query.
$results = $wpdb->get_results($query);

// Render menu page.
function custom_admin_plugin_render_menu_page()
{
    global $results, $now, $start_date, $end_date;
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <form method="post" action="" id="custom_admin_plugin_form">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="<?= ($_GET['start_date'] ?? $start_date) ?>">

            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" value="<?= ($_GET['end_date'] ?? $end_date) ?>">

            <input type="submit" name="action" value="Filter">
            <input type="submit" name="action" value="Today">
        </form>
    </div>
    <div class="wrap">
        <?php
        // Query database and display datatable here. 
        if ($results && count($results) > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>User ID</th>';
            echo '<th>Birthday</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($results as $result) {
                echo '<tr>';
                echo '<td>' . esc_html($result->user_id) . '</td>';
                echo '<td>' . esc_html($result->meta_value) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';

            echo '</table>';
        } else {
            echo '<p>No results found.</p>';
        }
        ?>
    </div>
<?php
}
// do_action('custom_admin_plugin_render_menu_page');

function get_user_data_details_update()
{

    global $wpdb;

    $test = $wpdb->prefix . 'usermeta';

    $today = date('m-d');

    echo "Today is :: $today";
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta where meta_key LIKE 'user_registration_birthday_field' or meta_key LIKE 'user_registration_phone_number' ");

    $results = $wpdb->get_results($query);

    foreach ($results as $result) {
        $uid = $result->user_id ?? 0;
        $metaKey = $result->meta_key ?? '';
        switch ($metaKey) {
            case 'user_registration_birthday_field':
                $get_today_birthday = date('Y-m-d', strtotime($result->meta_value));
                if (date('m-d', strtotime($get_today_birthday)) == $today) {
                    echo "<br>USER ID : " . $result->user_id . "<br>Birthday : " . date('Y-m-d', strtotime($get_today_birthday)) . "<br>";

                    $aa = $get_today_birthday;
                }
                break;
            case 'user_registration_phone_number':
                if ($uid == $get_phone) {
                    echo "Test : " . $result->meta_value . "<br>";

                    $phoneNumber = $result->meta_value;
                }
                break;
        }
    }

    echo "<br>Today is the birthday ($aa) of the owner of this phone number : $phoneNumber";
}


function get_user_data_details()
{

    global $wpdb;
    $ymd = date('Y-m-d');
    $md = date('m-d');
    $query = $wpdb->prepare("SELECT *
    FROM {$wpdb->prefix}usermeta WHERE meta_key LIKE 'user_registration_birthday_field' AND
      MONTH(meta_value) = MONTH(%s) AND
      DAY(meta_value) = DAY(%s)", $ymd, $ymd);
    $results = $wpdb->get_results($query);
    echo "<h3>Today birthday ($md)</h3>";

    foreach ($results as $result) {
        $userBirthday = date('d M Y', strtotime($result->meta_value));
        $phoneQuery = $wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key LIKE 'user_registration_phone_number' AND user_id = %s", $uid = $result->user_id);
        $phone = $wpdb->get_results($phoneQuery)[0]->meta_value ?? false;

        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>User ID</th>";
        echo "<th>Birthday</th>";
        echo "<th>Contact</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        echo "<tr>";
        echo "<th>$uid</th>";
        echo "<th>$userBirthday</th>";
        echo "<th>" . ($phone ? "<a href=\"tel:$phone\">$phone</a>" : 'Not found') . "</th>";
        echo "</tr>";
        echo "</tbody>";
        echo "</table>";
    }
}

do_action('get_user_data_details');
