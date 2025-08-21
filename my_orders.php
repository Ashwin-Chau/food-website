<?php 
include('functions/userfunctions.php');
include('includes/header.php'); 
include('functions/authenticate.php'); 
include('functions/algorithm.php');

// Ensure user is authenticated
if (!isset($_SESSION['auth_user']['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize search, sort, and date range variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$error = '';

// Validate sort column
$valid_sort_columns = ['id', 'order_no', 'total_price', 'created_at', 'order_time', 'status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'created_at';
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

// Fetch orders for the logged-in user
$user_id = $_SESSION['auth_user']['user_id'];
$result = getOrders($user_id);
$orders_array = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders_array[] = $row;
    }
}

// Apply date range filter
$filtered_items = $orders_array;
if ($start_date && $end_date && !$error) {
    $filtered_items = array_filter($filtered_items, function($item) use ($start_date, $end_date) {
        $created_at = substr($item['created_at'], 0, 10);
        return $created_at >= $start_date && $created_at <= $end_date;
    });
}

// Apply search
if (!empty($search)) {
    $filtered_items = binarySearchItemsById($filtered_items, $search);
}

// Apply sorting
$filtered_items = sortItems($filtered_items, $sort, $order);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="assets/css/style3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="section">
        <div class="container">
            <h2 class="my-orders-title">My Orders</h2>
            <!-- Display feedback messages -->
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php } elseif (isset($_GET['error'])) { ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php } elseif ($error) { ?>
                <div class="alert alert-danger alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php } ?>
            
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <a href="?sort=id&order=<?= ($sort == 'id' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    ID <span><?= $sort == 'id' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=order_no&order=<?= ($sort == 'order_no' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    Order No <span><?= $sort == 'order_no' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=total_price&order=<?= ($sort == 'total_price' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    Total Price <span><?= $sort == 'total_price' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=created_at&order=<?= ($sort == 'created_at' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    Order Date <span><?= $sort == 'created_at' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=order_time&order=<?= ($sort == 'order_time' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    Order Time <span><?= $sort == 'order_time' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>
                                <a href="?sort=status&order=<?= ($sort == 'status' && $order == 'ASC') ? 'DESC' : 'ASC' ?>&search=<?= htmlspecialchars($search) ?>&start_date=<?= htmlspecialchars($start_date) ?>&end_date=<?= htmlspecialchars($end_date) ?>">
                                    Status <span><?= $sort == 'status' ? ($order == 'ASC' ? '↑' : '↓') : '↑↓' ?></span>
                                </a>
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($filtered_items)) {
                        $currentDate = new DateTime();
                        foreach ($filtered_items as $item) {
                            // Split date and time
                            try {
                                $dateTime = new DateTime($item['created_at'], new DateTimeZone('Asia/Kathmandu'));
                                $order_date = $dateTime->format('Y-m-d');
                                $order_time = $dateTime->format('h:i A');
                            } catch (Exception $e) {
                                $order_date = htmlspecialchars($item['created_at']);
                                $order_time = 'N/A';
                            }
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
                                <td><?= htmlspecialchars($item['id']); ?></td>
                                <td><?= htmlspecialchars($item['order_no']); ?></td>
                                <td>Rs <?= number_format($item['total_price'], 2); ?></td>
                                <td><?= htmlspecialchars($order_date); ?></td>
                                <td><?= htmlspecialchars($order_time); ?></td>
                                <td class="status-<?= htmlspecialchars($statusClass) ?>">
                                    <?= htmlspecialchars($statusText) ?>
                                </td>
                                <td>
                                    <a href="view_order.php?o=<?= urlencode($item['order_no']); ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php if ($item['status'] == 0) { ?>
                                        <button class="btn btn-danger cancel-btn" 
                                                data-order-no="<?= htmlspecialchars($item['order_no']); ?>" 
                                                data-order-id="<?= htmlspecialchars($item['id']); ?>">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                No Orders Found
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cancellation Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <h3>Cancel Order</h3>
            <form id="cancelForm" method="POST" action="functions/authcode.php">
                <input type="hidden" name="order_id" id="cancelOrderId">
                <label for="cancel_reason">Reason for Cancellation:</label>
                <textarea name="cancel_reason" id="cancel_reason" required></textarea>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Confirm Cancel
                    </button>
                    <button type="button" class="btn btn-primary" onclick="closeModal()">
                        <i class="fas fa-arrow-left"></i> Close
                    </button>
                </div>
            </form>
        </div>
    </div>


    <?php include('includes/footer.php'); ?>
</body>
</html>