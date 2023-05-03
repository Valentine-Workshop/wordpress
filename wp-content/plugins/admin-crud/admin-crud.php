<?php

/**
 * Plugin Name: Admin CRUD
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
register_activation_hook(__FILE__, 'admin_crud_activate');
function admin_crud_activate()
{
    // Add code to execute when plugin is activated.
}

// Plugin deactivation hook.
register_deactivation_hook(__FILE__, 'admin_crud_deactivate');
function admin_crud_deactivate()
{
    // Add code to execute when plugin is deactivated.
}

function admin_crud_enqueue_styles()
{
    wp_enqueue_style('admin-crud-styles', plugin_dir_url(__FILE__) . 'style.css');
}


// Add menu page.
add_action('admin_menu', 'admin_crud_menu_page');
function admin_crud_menu_page()
{
    add_menu_page(
        'Admin CRUD',
        'Admin CRUD',
        'manage_options',
        'admin-crud',
        'admin_crud_table'
    );
    add_submenu_page(
        'admin-crud',
        'Add New',
        'Add New',
        'manage_options',
        'admin-crud-add',
        'admin_crud_add_new',
    );
    add_submenu_page(
        NULL,
        'Edit content',
        'Edit content',
        'manage_options',
        'admin-crud-edit',
        'admin_crud_edit',
    );
}



function admin_crud_count_status($results = [])
{
    $one = $two = $zero = $nine = 0;
    foreach ($results as $result) {
        switch ($result->status) {
            case 0:
                $zero++;
                break;
            case 1:
                $one++;
                break;
            case 2:
                $two++;
                break;
            case 1:
                $nine++;
                break;
        }
    }
    return [
        'zero' => $zero,
        'one' => $one,
        'two' => $two,
        'nine' => $nine,
    ];
}

// Render menu page.
function admin_crud_table()
{
    admin_crud_enqueue_styles();
    global $wpdb;
    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Build query.

    $query = "SELECT * FROM {$wpdb->prefix}admin_crud WHERE 1=1";
    $resultsAll = $wpdb->get_results($query . " AND  status <> 99");
    if (isset($_GET['status']) && $status == 0) {
        $query .= " AND  status = 0";
    } elseif (empty($status)) {
        $query .= " AND  status <> 99";
    } else {
        $query .= " AND  status = $status";
    }

    // Execute query.
    $results = $wpdb->get_results($query);
    $counter = admin_crud_count_status($resultsAll);
?>
    <div class="wrap">

        <h1 class="wp-heading-inline">
            <?php echo esc_html(get_admin_page_title()); ?></h1>

        <a href="admin.php?page=admin-crud-add" class="page-title-action">Add New</a>
        <span class="subtitle"><strong></strong></span>
        <hr class="wp-header-end">

        <h2 class="screen-reader-text">Filter list</h2>
        <ul class="subsubsub">
            <li class="all"><a href="admin.php?page=admin-crud" class="current" aria-current="page">All <span class="count">(<?= count($results) ?>)</span></a> |</li>
            <li class="status"><a href="admin.php?page=admin-crud&status=0">Draft <span class="count">(<?= $counter['zero'] ?>)</span></a> |</li>
            <li class="status"><a href="admin.php?page=admin-crud&status=1">Publish <span class="count">(<?= $counter['one'] ?>)</span></a> |</li>
            <li class="status"><a href="admin.php?page=admin-crud&status=2">Unpublish <span class="count">(<?= $counter['two'] ?>)</span></a> |</li>
            <li class="status"><a href="admin.php?page=admin-crud&status=99">Trash <span class="count">(<?= $counter['nine'] ?>)</span></a></li>
        </ul>

        <div class="clear"></div>
    </div>
    <div class="wrap">

        <?php
        echo '<table class="table table-striped table-hover">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Title</th>';
        echo '<th>Content</th>';
        echo '<th>Author</th>';
        echo '<th>Keyword</th>';
        if (empty($status) && !isset($_GET['status'])) {
            echo "<th>Status</th>";
        }
        echo '<th>Created</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        if ($results && count($results) > 0) {
            $count = 0;
            foreach ($results as $result) {
                if (empty($status) && !isset($_GET['status'])) {
                    switch ($result->status) {
                        case 0:
                            $statusSpan = "<td><span class=\"badge badge-secondary\">Draft</span></td>";
                            break;
                        case 1:
                            $statusSpan = "<td><span class=\"badge badge-success\">Publish</span></td>";
                            break;
                        case 2:
                            $statusSpan = "<td><span class=\"badge badge-danger\">Unpublish</span></td>";
                            break;
                        default:
                            $statusSpan = '-';
                            break;
                    }
                }
                $count++;
                $button = "<a href=\"admin/php?page=admin-crud-edit&id={$result->id}\">Edit</a>";
                $button .= " | Delete";
                echo '<tr>';
                echo "<td>$count</td>";
                echo '<td>' . esc_html($result->title ?? '-') . '</td>';
                echo '<td>' . ($result->content ?? '-') . '</td>';
                echo '<td>' . ($result->author ?? '-') . '</td>';
                echo '<td>' . ($result->keyword ?? '-') . '</td>';
                if (empty($status) && !isset($_GET['status'])) {
                    echo $statusSpan ?? '';
                }
                echo '<td>' . date('d M Y H:i:s', ($result->created)) . '</td>';
                echo "<td>$button</td>";
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td class="text-center" colspan="7">No results found.</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '<tfoot>';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Title</th>';
        echo '<th>Content</th>';
        echo '<th>Author</th>';
        echo '<th>Keyword</th>';
        if (empty($status) && !isset($_GET['status'])) {
            echo "<th>Status</th>";
        }
        echo '<th>Created</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';
        ?>
    </div>

<?php
}


function admin_crud_add_query()
{
    global $wpdb, $status;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';
    $status = $_POST['status'] ?? 0;
    $time = time();
    $table = $wpdb->prefix . 'admin_crud';
    $data = array(
        'title' => $title,
        'content' => $content,
        'author' => $author,
        'status' => $status,
        'created' => $time,
        'meta' => NULL,
        'keyword' => NULL,
        'group_id' => NULL,
    );
    $result = $wpdb->insert($table, $data);
    if ($result) {
        set_transient('admin_crud_flash', $result ? 'New content created - ' . $title : 'Insert failed');
        echo "<script>location.href='admin.php?page=admin-crud-add';</script>";
        exit;
    } else {
        set_transient('admin_crud_flash', $wpdb->last_error);
    }
}


function admin_crud_update_query($id = 0)
{
    global $wpdb, $status;
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? '';
    $status = $_POST['status'] ?? 0;
    $time = time();
    $table = $wpdb->prefix . 'admin_crud';
    $data = array(
        'title' => $title,
        'content' => $content,
        'author' => $author,
        'status' => $status,
        'created' => $time,
        'meta' => NULL,
        'keyword' => NULL,
        'group_id' => NULL,
    );
    $result = $wpdb->update($table, $data, ['id' => $id]);
    if ($result) {
        set_transient('admin_crud_flash', $result ? 'Content updated' : 'Update failed');
        echo "<script>location.href='admin.php?page=admin-crud-edit&id=$id';</script>";
        exit;
    } else {
        set_transient('admin_crud_flash', $wpdb->last_error);
    }
}

function admin_crud_edit()
{
    global $wpdb;
    $id = $_GET['id'] ?? 0;
    if (isset($_POST['action']) && $_POST['action'] == 'Update') {
        admin_crud_update_query($id);
    }
    $data = get_transient('admin_crud_flash');
    if ($data) {
        echo "<div class='notice'>$data</div>";
        delete_transient('admin_crud_flash');
    }
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}admin_crud WHERE id = %s", $id);
    $result = $wpdb->get_results($query)[0] ?? false;
?>
    <div class="wrap">

        <h1 class="wp-heading-inline">New content</h1>
        <form action="" method="post" claas="row">
            <div class="form-group col-12">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="<?= $result->title ?? '' ?>" class="form-control" placeholder="Title">
            </div>
            <div class="form-group col-12">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" placeholder="Content"><?= $result->content ?? '' ?></textarea>
            </div>
            <div class="form-group col-12">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" value="<?= $result->author ?? '' ?>" class="form-control" placeholder="Author">
            </div>
            <div class="form-group col-12">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" placeholder="Status">
                    <option value="0" <?= $result->status == 0 ? 'selected' : '' ?>>Draft</option>
                    <option value="1" <?= $result->status == 1 ? 'selected' : '' ?>>Publish</option>
                    <option value="2" <?= $result->status == 2 ? 'selected' : '' ?>>Unpublish</option>
                </select>
            </div>
            <div class="form-group col-12">
                <input type="submit" value="Update" name="action" class="form-control btn btn-primary">
            </div>
        </form>
    </div>
<?php

}

function admin_crud_add_new()
{
    if (isset($_POST['action']) && $_POST['action'] == 'Create') {
        admin_crud_add_query();
    }
    admin_crud_enqueue_styles();
    $data = get_transient('admin_crud_flash');
    if ($data) {
        echo "<div class='notice'>$data</div>";
        delete_transient('admin_crud_flash');
    }
?>
    <div class="wrap">

        <h1 class="wp-heading-inline">New content</h1>
        <form action="" method="post" claas="row">
            <div class="form-group col-12">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" value="" class="form-control" placeholder="Title">
            </div>
            <div class="form-group col-12">
                <label for="content">Content</label>
                <textarea name="content" id="content" class="form-control" placeholder="Content"></textarea>
            </div>
            <div class="form-group col-12">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" value="" class="form-control" placeholder="Author">
            </div>
            <div class="form-group col-12">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" placeholder="Status">
                    <option value="0">Draft</option>
                    <option value="1">Publish</option>
                    <option value="2">Unpublish</option>
                </select>
            </div>
            <div class="form-group col-12">
                <input type="submit" value="Create" name="action" class="form-control btn btn-primary">
            </div>
        </form>
    </div>
<?php
}

do_action('admin_crud_table');
