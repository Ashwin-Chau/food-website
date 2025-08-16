<?php 
include('functions/userfunctions.php');
include('includes/header.php'); 
include('functions/authenticate.php'); 

if (isset($_GET['o'])) {
    $order_no = mysqli_real_escape_string($con, $_GET['o']);
    $orderData = checkOrderNoValid($order_no);
    if (mysqli_num_rows($orderData) == 0) {
        echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">Invalid order number.</div>';
        die();
    }
} else {
    echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">Order number not provided.</div>';
    die();
}

$data = mysqli_fetch_array($orderData);

// Debug: Log status value (remove after testing)
if (!isset($data['status'])) {
    echo '<div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px;">Warning: Status field is missing in order data.</div>';
}

// Define status map
$statusMap = [
    0 => ['class' => 'under-process', 'text' => 'Under Process'],
    1 => ['class' => 'out-for-delivery', 'text' => 'Out for Delivery'],
    2 => ['class' => 'delivered', 'text' => 'Delivered'],
    3 => ['class' => 'cancelled', 'text' => 'Cancelled'],
];

// Get status safely
$statusClass = isset($data['status']) && array_key_exists($data['status'], $statusMap) 
    ? $statusMap[$data['status']]['class'] 
    : 'cancelled';
$statusText = isset($data['status']) && array_key_exists($data['status'], $statusMap) 
    ? $statusMap[$data['status']]['text'] 
    : 'Unknown';

// Split date and time
try {
    $dateTime = new DateTime($data['created_at'], new DateTimeZone('Asia/Kathmandu'));
    $order_date = $dateTime->format('Y-m-d');
    $order_time = $dateTime->format('h:i A');
} catch (Exception $e) {
    $order_date = htmlspecialchars($data['created_at']);
    $order_time = 'N/A';
}
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f7fa;
        color: #333333;
        line-height: 1.6;
        padding-top: 70px;
    }

    .header-section {
        background: linear-gradient(135deg, #3b82f6, #2b6cb0);
        padding: 15px 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        width: 100%;
    }

    .breadcrumb a {
        color: #ffffff;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 400;
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: #facc15;
    }

    .section {
        padding: 40px 0;
    }

    .order-container {
        margin-top: 20px;
        width: 100%;
    }

    .order-section {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .order-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        background: linear-gradient(135deg, #2b6cb0, #1e4976);
        padding: 15px 20px;
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .back-button, .invoice-button {
        padding: 8px 18px;
        background-color: #facc15;
        color: #1f2937;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background-color 0.3s ease, transform 0.2s ease;
        margin-left: 10px;
    }

    .back-button:hover, .invoice-button:hover {
        background-color: #eab308;
        transform: scale(1.03);
    }

    .order-body {
        padding: 25px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }

    .user-details, .order-details {
        background: #f9fafb;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .user-details h4, .order-details h4 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #1e4976;
        margin-bottom: 15px;
        position: relative;
    }

    .user-details h4::after, .order-details h4::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: #facc15;
    }

    .user-details hr, .order-details hr {
        border: 0;
        border-top: 1px solid #e5e7eb;
        margin: 15px 0;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }

    .detail-row.single {
        grid-template-columns: 1fr;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-row label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #374151;
        margin-bottom: 6px;
    }

    .detail-box {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #1f2937;
        transition: background-color 0.3s ease;
    }

    .detail-box:hover {
        background-color: #eff6ff;
    }

    .order-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    .order-table th, .order-table td {
        border: 1px solid #e5e7eb;
        padding: 12px;
        text-align: left;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .order-table th {
        background-color: #eff6ff;
        font-weight: 600;
        color: #1e4976;
    }

    .order-table td {
        background-color: #ffffff;
        transition: background-color 0.2s ease;
    }

    .order-table tr:hover td {
        background-color: #f9fafb;
    }

    .food-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .order-table img {
        width: 50px;
        height: 50px;
        border-radius: 6px;
        object-fit: cover;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }

    .food-name {
        font-size: 0.9rem;
        color: #1f2937;
        font-weight: 500;
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .total-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e4976;
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
    }

    .payment-mode, .status-box {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
        margin-bottom: 15px;
        transition: background-color 0.3s ease;
    }

    .payment-mode:hover, .status-box:hover {
        background-color: #eff6ff;
    }

    .status-box {
        position: relative;
        padding-left: 30px;
    }

    .status-box::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ccc;
    }

    .status-box.under-process::before { background-color: #facc15; }
    .status-box.out-for-delivery::before { background-color: #22c55e; }
    .status-box.delivered::before { background-color: #0000FF; }
    .status-box.cancelled::before { background-color: #ef4444; }

    @media (max-width: 768px) {
        .order-body {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .detail-row {
            grid-template-columns: 1fr;
        }

        .order-header {
            font-size: 1.3rem;
            padding: 12px 15px;
        }

        .back-button, .invoice-button {
            font-size: 0.85rem;
            padding: 8px 14px;
        }

        .user-details, .order-details {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .order-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .order-table th, .order-table td {
            padding: 8px;
            font-size: 0.85rem;
        }

        .order-table img {
            width: 40px;
            height: 40px;
        }

        .food-name {
            font-size: 0.85rem;
        }

        .detail-row label, .detail-box, .total-price, .payment-mode, .status-box {
            font-size: 0.85rem;
        }
    }
</style>

<div class="section">
    <div class="container">
        <div class="order-container">
            <div class="order-section">
                <div class="order-header">
                    <span>View Order</span>
                    <div>
                        <a href="generate_invoice.php?o=<?= htmlspecialchars($order_no); ?>" class="invoice-button" target="_blank">Download Invoice</a>
                        <a href="my_orders.php" class="back-button" aria-label="Back to My Orders">Back</a>
                    </div>
                </div>
                <div class="order-body">
                    <div class="user-details">
                        <h4>User Details</h4>
                        <hr>
                        <div class="detail-row">
                            <div class="detail-item">
                                <label>Name</label>
                                <div class="detail-box"><?= htmlspecialchars($data['name']); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Email</label>
                                <div class="detail-box"><?= htmlspecialchars($data['email']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-item">
                                <label>Phone</label>
                                <div class="detail-box"><?= htmlspecialchars($data['phone']); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Zipcode</label>
                                <div class="detail-box"><?= htmlspecialchars($data['zipcode']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-item">
                                <label>Order No</label>
                                <div class="detail-box"><?= htmlspecialchars($data['order_no']); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Address</label>
                                <div class="detail-box"><?= htmlspecialchars($data['address']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-item">
                                <label>Order Date</label>
                                <div class="detail-box"><?= htmlspecialchars($order_date); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Order Time</label>
                                <div class="detail-box"><?= htmlspecialchars($order_time); ?></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-item">
                                <label>Cancel Reason</label>
                                <div class="detail-box"><?= htmlspecialchars($data['cancel_reason'] ?: 'N/A'); ?></div>
                            </div>
                            <div class="detail-item">
                                <label>Payment Id</label>
                                <div class="detail-box"><?= htmlspecialchars($data['payment_id']); ?></div>
                            </div>
                        </div>
                        <div class="detail-row single">
                            <div class="detail-item">
                                <label>Notes</label>
                                <div class="detail-box"><?= htmlspecialchars($data['notes'] ?: 'N/A'); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="order-details">
                        <h4>Order Details</h4>
                        <hr>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Food Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $customer_id = $_SESSION['auth_user']['user_id'];
                                $order_query = "SELECT o.id as oid, o.order_no, o.customer_id, oi.*, oi.quantity as order_quantity, f.* 
                                    FROM orders o, order_items oi, food_items f 
                                    WHERE o.customer_id='$customer_id' AND oi.order_id=o.id AND f.id=oi.food_items_id 
                                    AND o.order_no='$order_no'";
                                $order_query_run = mysqli_query($con, $order_query);
                                if (mysqli_num_rows($order_query_run) == 0) {
                                    echo '<tr><td colspan="4">No items found for this order.</td></tr>';
                                } else {
                                    foreach ($order_query_run as $item) {
                                        $item_total = $item['order_quantity'] * $item['price'];
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="food-item">
                                                    <img src="http://localhost/Food/Uploads/<?= htmlspecialchars($item['image']); ?>" width="50px" height="50px" alt="<?= htmlspecialchars($item['name']); ?>" loading="lazy">
                                                    <span class="food-name"><?= htmlspecialchars($item['name']); ?></span>
                                                </div>
                                            </td>
                                            <td>Rs <?= htmlspecialchars($item['price']); ?></td>
                                            <td><?= htmlspecialchars($item['order_quantity']); ?></td>
                                            <td>Rs <?= number_format($item_total); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <hr>
                        <div class="total-price">
                            <span>Total Price: </span>
                            <span>Rs <?= htmlspecialchars($data['total_price']); ?></span>
                        </div>
                        <hr>
                        <label>Payment Mode</label>
                        <div class="payment-mode"><?= htmlspecialchars($data['payment_mode']); ?></div>
                        <label>Status</label>
                        <div class="status-box <?= htmlspecialchars($statusClass) ?>">
                            <?= htmlspecialchars($statusText) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>