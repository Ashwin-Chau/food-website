<?php 
include_once('../middleware/adminMiddleware.php');
include_once('../functions/myfunctions.php');
include_once('../functions/algorithm.php');
include_once('includes/header.php');

// Initialize search, sort, and date range variables
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$error = '';

// Validate sort column
$valid_sort_columns = ['id', 'name', 'order_no', 'total_price', 'created_at', 'status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'id';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

// Validate date range
if ($start_date && $end_date) {
    $start = DateTime::createFromFormat('Y-m-d', $start_date);
    $end = DateTime::createFromFormat('Y-m-d', $end_date);
    if (!$start || !$end) {
        $error = 'Invalid date format.';
        $start_date = $end_date = '';
    } elseif ($start > $end) {
        $error = 'End date must be after start date.';
        $start_date = $end_date = '';
    }
}

// Fetch all orders
$result = getPendingOrders();
$orders_array = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders_array[] = $row;
    }
}

// Apply search by order ID
$filtered_items = $orders_array;
if (!empty($search)) {
    if (!is_numeric($search)) {
        $error = 'Search must be a valid order ID (numeric).';
        $filtered_items = [];
    } else {
        $filtered_items = array_filter($filtered_items, function($item) use ($search) {
            return strpos((string)$item['id'], $search) !== false;
        });
    }
}

// Apply date range filter
if ($start_date && $end_date && !$error) {
    $filtered_items = array_filter($filtered_items, function($item) use ($start_date, $end_date) {
        $created_at = substr($item['created_at'], 0, 10);
        return $created_at >= $start_date && $created_at <= $end_date;
    });
}

// Apply sorting
if ($sort === 'created_at' && $order === 'ASC') {
    $filtered_items = fcfsSort($filtered_items);
} else {
    $filtered_items = sortItems($filtered_items, $sort, $order);
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="text-white">Pending Orders
                        <a href="order_history.php" class="btn btn-warning float-end btn-sm">Cancelled</a>
                        <a href="delivered.php" class="btn btn-warning float-end btn-sm me-2">Delivered</a>
                        <a href="out_for_delivey.php" class="btn btn-warning float-end btn-sm me-2">Out for delivary</a>
                    </h4>
                </div>
                <div class="card-body" id="orders_table">
                    <!-- Display error if any -->
                    <?php if ($error) { ?>
                        <div class="alert alert-danger alert-error">
                            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                        </div>
                    <?php } ?>
                    <!-- Search Form -->
                    <div class="mb-3">
                        <form method="GET" class="row g-2 align-items-center">
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search by Order ID" 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary btn-sm" 
                                        onclick="window.location.href='?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>'">
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
                    <!-- Date Range Form -->
                    <div class="date-range-form">
                        <form method="GET" class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" 
                                       value="<?= htmlspecialchars($start_date) ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" 
                                       value="<?= htmlspecialchars($end_date) ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-filter me-2"></i>Filter</button>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-lg w-100" 
                                        onclick="window.location.href='?search=<?= htmlspecialchars($search) ?>&sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>'">
                                    <i class="fas fa-calendar-times me-2"></i>Clear Dates
                                </button>
                            </div>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                            <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
                        </form>
                    </div>
                    <!-- Orders Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <a href="?sort=id&order=<?= ($sort == 'id' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        ID <?= $sort == 'id' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=name&order=<?= ($sort == 'name' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        User <?= $sort == 'name' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=order_no&order=<?= ($sort == 'order_no' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        Order No <?= $sort == 'order_no' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=total_price&order=<?= ($sort == 'total_price' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        Total Price <?= $sort == 'total_price' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=created_at&order=<?= ($sort == 'created_at' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        Date <?= $sort == 'created_at' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="?sort=status&order=<?= ($sort == 'status' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                        Status <?= $sort == 'status' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?>
                                    </a>
                                </th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($filtered_items)) { ?>
                                <?php foreach ($filtered_items as $item) { ?>
                                    <?php
                                    $statusMap = [
                                        0 => ['class' => 'under-process', 'text' => 'Under Process'],
                                        1 => ['class' => 'out-for-delivery', 'text' => 'Out for Delivery'],
                                        2 => ['class' => 'delivered', 'text' => 'Delivered'],
                                        3 => ['class' => 'cancelled', 'text' => 'Cancelled'],
                                    ];
                                    $statusClass = 'cancelled';
                                    $statusText = 'Cancelled';
                                    if (isset($item['status']) && array_key_exists($item['status'], $statusMap)) {
                                        $statusClass = $statusMap[$item['status']]['class'];
                                        $statusText = $statusMap[$item['status']]['text'];
                                    }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['id']) ?></td>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= htmlspecialchars($item['order_no']) ?></td>
                                        <td>Rs <?= number_format($item['total_price']) ?></td>
                                        <td><?= htmlspecialchars($item['created_at']) ?></td>
                                        <td class="status-<?= htmlspecialchars($statusClass) ?>">
                                            <?= htmlspecialchars($statusText) ?>
                                            <?php if ($item['status'] == 3 && !empty($item['cancel_reason'])) { ?>
                                                <div class="text-muted small">Reason: <?= htmlspecialchars($item['cancel_reason']) ?></div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="view_order.php?o=<?= htmlspecialchars($item['order_no']) ?>" 
                                               class="btn btn-primary btn-sm">View Details</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7">No Pending Orders Found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>