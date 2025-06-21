<?php 
include_once('../middleware/adminMiddleware.php');
include_once('../functions/myfunctions.php');
include_once('../functions/algorithm.php');
include_once('includes/header.php');

// Initialize search and sort variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Validate sort column
$valid_sort_columns = ['id', 'name', 'status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'id';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

// Fetch all menu items
$result = getAll("menu");
$menu_items_array = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menu_items_array[] = $row;
    }
}

// Apply search and sort
$filtered_items = !empty($search) ? binarySearchItems($menu_items_array, $search, ['name']) : $menu_items_array;
$filtered_items = sortItems($filtered_items, $sort, $order);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Menu</h4>
                </div>
                <div class="card-body" id="menu_table">
                    <!-- Search Form -->
                    <div class="mb-3">
                        <form method="GET" class="row g-2 align-items-center">
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by name" 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary btn-sm" 
                                        onclick="window.location.href='?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>'">
                                    Clear
                                </button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary btn-sm" 
                                        onclick="window.location.href='?sort=id&order=ASC'">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <a href="?sort=id&order=<?= ($sort == 'id' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>">
                                        ID <?= $sort == 'id' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=name&order=<?= ($sort == 'name' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>">
                                        Name <?= $sort == 'name' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>Image</th>
                                <th>
                                    <a href="?sort=status&order=<?= ($sort == 'status' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>">
                                        Status <?= $sort == 'status' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($filtered_items)) {
                                foreach ($filtered_items as $item) {
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['id']); ?></td>
                                        <td><?= htmlspecialchars($item['name']); ?></td>
                                        <td>
                                            <img src="../Uploads/<?= htmlspecialchars($item['image']); ?>" 
                                                 width="50px" height="50px" 
                                                 alt="<?= htmlspecialchars($item['name']); ?>">
                                        </td>
                                        <td>
                                            <?= $item['status'] == '0' ? "Visible" : "Hidden" ?>
                                        </td>
                                        <td>
                                            <a href="edit_menu.php?id=<?= htmlspecialchars($item['id']); ?>" 
                                               class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete_menu_btn" 
                                                    value="<?= htmlspecialchars($item['id']); ?>">Delete</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>