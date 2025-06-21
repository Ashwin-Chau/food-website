<?php
if (!file_exists('../functions/myfunctions.php')) {
    die('Error: myfunctions.php not found at ../functions/myfunctions.php');
}
include_once('../middleware/adminMiddleware.php');
include_once('../functions/myfunctions.php');
include_once('../functions/algorithm.php');
include_once('includes/header.php');

if (!function_exists('getTotalSales') || !function_exists('getPopularMenuItems') || !function_exists('getOrderTimes')) {
    die('Error: Required functions not defined in myfunctions.php');
}

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Sorting and filtering
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'period';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$item_sort = isset($_GET['item_sort']) ? $_GET['item_sort'] : 'quantity_sold';
$item_order = isset($_GET['item_order']) ? $_GET['item_order'] : 'DESC';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : '';
$error = '';

// Validate sorting parameters
$valid_sort_columns = ['period', 'total_sales', 'order_count'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'period';
$order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

$valid_item_sort_columns = ['item_name', 'quantity_sold', 'quantity_price', 'total_revenue'];
$item_sort = in_array($item_sort, $valid_item_sort_columns) ? $item_sort : 'quantity_sold';
$item_order = strtoupper($item_order) === 'DESC' ? 'DESC' : 'ASC';

$valid_filter_types = ['daily', 'last7days', 'last15days', 'last30days'];
$filter_type = in_array($filter_type, $valid_filter_types) ? $filter_type : '';

// Initialize variables
$sales_data = [];
$popular_items = [];
$order_times = [];
$use_manual_dates = false;

// Set dates based on filter_type
if ($filter_type) {
    $today = new DateTime();
    $end_date = $today->format('Y-m-d');
    switch ($filter_type) {
        case 'daily':
            $start_date = $end_date;
            break;
        case 'last7days':
            $start_date = $today->modify('-6 days')->format('Y-m-d');
            break;
        case 'last15days':
            $start_date = $today->modify('-14 days')->format('Y-m-d');
            break;
        case 'last30days':
            $start_date = $today->modify('-29 days')->format('Y-m-d');
            break;
    }
} elseif ($start_date && $end_date) {
    $use_manual_dates = true;
}

// Fetch data
$fetch_all = empty($filter_type) && empty($start_date) && empty($end_date);
if ($fetch_all || ($start_date && $end_date)) {
    if (!$fetch_all) {
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

    if (!$error || $fetch_all) {
        // Fetch sales data
        $result = getTotalSales(null, 'custom', null, null, null, $fetch_all ? '' : $start_date, $fetch_all ? '' : $end_date);
        if ($result === false) {
            $error = 'Failed to fetch sales data: ' . mysqli_error($con);
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $sales_data[] = [
                    'period' => $row['period'],
                    'total_sales' => (float)$row['total_sales'],
                    'order_count' => (int)$row['order_count']
                ];
            }
            error_log("Sales Data Before Sorting: " . print_r($sales_data, true));
        }

        // Fetch popular menu items
        $popular_result = getPopularMenuItems($fetch_all ? '' : $start_date, $fetch_all ? '' : $end_date);
        if ($popular_result === false) {
            $error = 'Failed to fetch popular menu items: ' . mysqli_error($con);
        } else {
            while ($row = mysqli_fetch_assoc($popular_result)) {
                $quantity_price = $row['quantity_sold'] > 0 ? $row['total_revenue'] / $row['quantity_sold'] : 0;
                $popular_items[] = [
                    'item_name' => $row['item_name'],
                    'quantity_sold' => (int)$row['quantity_sold'],
                    'quantity_price' => (float)$quantity_price,
                    'total_revenue' => (float)$row['total_revenue']
                ];
            }
            error_log("Popular Items Before Sorting: " . print_r($popular_items, true));
        }

        // Fetch order times
        $order_times_result = getOrderTimes($fetch_all ? '' : $start_date, $fetch_all ? '' : $end_date);
        if ($order_times_result === false) {
            $error = 'Failed to fetch order times: ' . mysqli_error($con);
        } else {
            while ($row = mysqli_fetch_assoc($order_times_result)) {
                $order_times[] = [
                    'hour' => (int)$row['hour'],
                    'order_count' => (int)$row['order_count']
                ];
            }
        }
    }
}

// Apply sorting
$sales_data = sortItems($sales_data, $sort, $order);
$popular_items = sortItems($popular_items, $item_sort, $item_order);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous">
</head>
<body>
    <div class="container py-4">
        <h2 class="mb-4">Sales Dashboard</h2>
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } elseif (isset($_GET['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } elseif ($error) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="filter_type" class="form-label">Filter Type</label>
                    <select name="filter_type" id="filter_type" class="form-select" onchange="updateDateInputs(this.value)">
                        <option value="" <?= $filter_type == '' ? 'selected' : '' ?>>All Data</option>
                        <option value="daily" <?= $filter_type == 'daily' ? 'selected' : '' ?>>Daily</option>
                        <option value="last7days" <?= $filter_type == 'last7days' ? 'selected' : '' ?>>Last 7 Days</option>
                        <option value="last15days" <?= $filter_type == 'last15days' ? 'selected' : '' ?>>Last 15 Days</option>
                        <option value="last30days" <?= $filter_type == 'last30days' ? 'selected' : '' ?>>Last 30 Days</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($use_manual_dates ? $start_date : '') ?>">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($use_manual_dates ? $end_date : '') ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filter</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&item_sort=<?= htmlspecialchars($item_sort) ?>&item_order=<?= htmlspecialchars($item_order) ?>&filter_type='"><i class="fas fa-calendar-times me-2"></i>Clear Dates</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="window.location.href='?sort=period&order=DESC&item_sort=quantity_sold&item_order=DESC&filter_type='"><i class="fas fa-sync-alt me-2"></i>Reset</button>
                </div>
            </div>
            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
            <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
            <input type="hidden" name="item_sort" value="<?= htmlspecialchars($item_sort) ?>">
            <input type="hidden" name="item_order" value="<?= htmlspecialchars($item_order) ?>">
        </form>

        <!-- Sales Overview -->
        <h3 class="mt-5 mb-3">Sales Overview</h3>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">
                            <a href="?sort=period&order=<?= ($sort == 'period' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&item_sort=<?= htmlspecialchars($item_sort) ?>&item_order=<?= htmlspecialchars($item_order) ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Period <?= $sort == 'period' ? ($order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=total_sales&order=<?= ($sort == 'total_sales' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&item_sort=<?= htmlspecialchars($item_sort) ?>&item_order=<?= htmlspecialchars($item_order) ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Total Sales <?= $sort == 'total_sales' ? ($order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=order_count&order=<?= ($sort == 'order_count' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&item_sort=<?= htmlspecialchars($item_sort) ?>&item_order=<?= htmlspecialchars($item_order) ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Order Count <?= $sort == 'order_count' ? ($order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sales_data)) { ?>
                        <?php foreach ($sales_data as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['period']) ?></td>
                                <td>Rs <?= number_format($item['total_sales'], 2) ?></td>
                                <td><?= htmlspecialchars($item['order_count']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="3" class="text-center py-4">No Sales Data Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Popular Menu Items -->
        <h3 class="mt-5 mb-3">Popular Menu Items</h3>
        <div class="table-responsive" id="popular_menu">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">
                            <a href="?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&item_sort=item_name&item_order=<?= ($item_sort == 'item_name' && $item_order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Item Name <?= $item_sort == 'item_name' ? ($item_order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&item_sort=quantity_sold&item_order=<?= ($item_sort == 'quantity_sold' && $item_order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Quantity Sold <?= $item_sort == 'quantity_sold' ? ($item_order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&item_sort=quantity_price&item_order=<?= ($item_sort == 'quantity_price' && $item_order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Quantity Price (Rs) <?= $item_sort == 'quantity_price' ? ($item_order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="?sort=<?= htmlspecialchars($sort) ?>&order=<?= htmlspecialchars($order) ?>&item_sort=total_revenue&item_order=<?= ($item_sort == 'total_revenue' && $item_order == 'ASC') ? 'DESC' : 'ASC' ?>&filter_type=<?= htmlspecialchars($filter_type) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>" class="text-white text-decoration-none">
                                Total Revenue (Rs) <?= $item_sort == 'total_revenue' ? ($item_order == 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort"></i>' ?>
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($popular_items)) { ?>
                        <?php foreach ($popular_items as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['item_name']) ?></td>
                                <td><?= htmlspecialchars($item['quantity_sold']) ?></td>
                                <td>Rs <?= number_format($item['quantity_price'], 2) ?></td>
                                <td>Rs <?= number_format($item['total_revenue'], 2) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">No Menu Items Data Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Order Times -->
        <h3 class="mt-5 mb-3">Order Times</h3>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Hour</th>
                        <th scope="col">Order Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($order_times)) { ?>
                        <?php foreach ($order_times as $item) { ?>
                            <tr>
                                <td><?= htmlspecialchars($item['hour']) ?>:00</td>
                                <td><?= htmlspecialchars($item['order_count']) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="2" class="text-center py-4">No Order Times Data Found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function updateDateInputs(filterType) {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const today = new Date();
            let startDate, endDate;

            switch (filterType) {
                case 'daily':
                    startDate = endDate = today.toISOString().split('T')[0];
                    break;
                case 'last7days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 6);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                case 'last15days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 14);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                case 'last30days':
                    endDate = today.toISOString().split('T')[0];
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 29);
                    startDate = startDate.toISOString().split('T')[0];
                    break;
                default:
                    startDate = endDate = '';
                    break;
            }

            startDateInput.value = startDate;
            endDateInput.value = endDate;
        }

        // Initialize date inputs
        document.addEventListener('DOMContentLoaded', () => {
            const filterType = document.getElementById('filter_type').value;
            if (filterType) {
                updateDateInputs(filterType);
            }
        });
    </script>
    <?php include('includes/footer.php'); ?>
</body>
</html>