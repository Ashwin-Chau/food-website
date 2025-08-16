<?php
include('../config/dbcon.php');

if (!isset($_GET['o'])) {
    echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">Order number not provided.</div>';
    die();
}

function checkOrderNoValid($orderNo)
{
    global $con;
    $query = "SELECT * FROM orders WHERE order_no='$orderNo' ";
    return mysqli_query($con, $query);
}

$order_no = mysqli_real_escape_string($con, $_GET['o']);
$orderData = checkOrderNoValid($order_no);
if (mysqli_num_rows($orderData) == 0) {
    echo '<div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px;">Invalid order number.</div>';
    die();
}

$data = mysqli_fetch_array($orderData);

// Split date and time
try {
    $dateTime = new DateTime($data['created_at'], new DateTimeZone('Asia/Kathmandu'));
    $order_date = $dateTime->format('Y-m-d');
    $order_time = $dateTime->format('h:i A');
} catch (Exception $e) {
    $order_date = htmlspecialchars($data['created_at']);
    $order_time = 'N/A';
}

// Define status map
$statusMap = [
    0 => ['text' => 'Under Process'],
    1 => ['text' => 'Out for Delivery'],
    2 => ['text' => 'Delivered'],
    3 => ['text' => 'Cancelled'],
];

// Get status safely
$statusText = isset($data['status']) && array_key_exists($data['status'], $statusMap)
    ? $statusMap[$data['status']]['text']
    : 'Unknown';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Order <?= htmlspecialchars($data['order_no']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header {
            background: #2b6cb0;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .invoice-body {
            padding: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .detail-item label {
            font-weight: bold;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .order-table th {
            background: #eff6ff;
        }
        .total-price {
            font-weight: bold;
            text-align: right;
        }
        .status-box, .payment-mode {
            margin-top: 10px;
        }
        img {
            max-width: 50px;
            height: auto;
        }
        .no-print {
            margin-top: 20px;
        }
        .no-print button {
            padding: 8px 16px;
            margin-right: 10px;
            background-color: #2b6cb0;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .no-print button:hover {
            background-color: #1e4976;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body class="invoice-print">
    <div class="invoice-container">
        <div class="invoice-header">
            <h2>Invoice</h2>
            <p>Order No: <?= htmlspecialchars($data['order_no']); ?></p>
        </div>
        <div class="invoice-body">
            <h3>User Details</h3>
            <div class="detail-row">
                <div class="detail-item"><label>Name:</label> <?= htmlspecialchars($data['name']); ?></div>
                <div class="detail-item"><label>Email:</label> <?= htmlspecialchars($data['email']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-item"><label>Phone:</label> <?= htmlspecialchars($data['phone']); ?></div>
                <div class="detail-item"><label>Zipcode:</label> <?= htmlspecialchars($data['zipcode']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-item"><label>Address:</label> <?= htmlspecialchars($data['address']); ?></div>
                <div class="detail-item"><label>Order Date:</label> <?= htmlspecialchars($order_date); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-item"><label>Order Time:</label> <?= htmlspecialchars($order_time); ?></div>
                <div class="detail-item"><label>Cancel Reason:</label> <?= htmlspecialchars($data['cancel_reason'] ?: 'N/A'); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-item"><label>Notes:</label> <?= htmlspecialchars($data['notes'] ?: 'N/A'); ?></div>
            </div>
            <h3>Order Details</h3>
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
                    $order_query = "SELECT o.id as oid, o.order_no, o.customer_id, oi.*, oi.quantity as order_quantity, f.* 
                        FROM orders o, order_items oi, food_items f 
                        WHERE oi.order_id=o.id AND f.id=oi.food_items_id 
                        AND o.order_no='$order_no'";
                    $order_query_run = mysqli_query($con, $order_query);
                    if (mysqli_num_rows($order_query_run) > 0) {
                        foreach ($order_query_run as $item) {
                            $item_total = $item['order_quantity'] * $item['price'];
                            ?>
                            <tr>
                                <td>
                                    <img src="http://localhost/Food/Uploads/<?= htmlspecialchars($item['image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>">
                                    <?= htmlspecialchars($item['name']); ?>
                                </td>
                                <td>Rs <?= htmlspecialchars($item['price']); ?></td>
                                <td><?= htmlspecialchars($item['order_quantity']); ?></td>
                                <td>Rs <?= number_format($item_total); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="4">No items found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="total-price">Total Price: Rs <?= htmlspecialchars($data['total_price']); ?></div>
            <div class="payment-mode"><strong>Payment Mode:</strong> <?= htmlspecialchars($data['payment_mode']); ?></div>
            <div class="payment-mode"><strong>Payment Id:</strong> <?= htmlspecialchars($data['payment_id']); ?></div>
            <div class="status-box"><strong>Status:</strong> <?= htmlspecialchars($statusText); ?></div>
        </div>
        <div class="no-print">
            <button onclick="window.print()">Print/Save as PDF</button>
            <button onclick="window.close()">Close</button>
        </div>
    </div>
</body>
</html>