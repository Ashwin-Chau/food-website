<?php
include('../config/dbcon.php');
include('../functions/myfunctions.php');
include('includes/header.php');

if (isset($_GET['o'])) {
    $order_no = mysqli_real_escape_string($con, $_GET['o']);
    $orderData = checkOrderNoValid($order_no);
    if (mysqli_num_rows($orderData) <= 0) {
        ?>
        <h4>Something went wrong</h4>
        <?php
        die();
    }
} else {
    ?>
    <h4>Something went wrong</h4>
    <?php
    die();
}

$data = mysqli_fetch_array($orderData);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <span class="text-white fs-4">View Order</span>
                    <div class="float-end">
                        <a href="generate_invoice.php?o=<?= htmlspecialchars($order_no); ?>" class="btn btn-sm" style="background-color: #facc15; color: #1f2937; margin-right: 10px;" target="_blank"><i class="fa fa-download"></i> Download Invoice</a>
                        <a href="orders.php" class="btn btn-warning btn-sm"><i class="fa fa-reply"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>User Details</h4>
                            <hr style="background-color: black">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Name</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['name']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Email</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['email']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Phone</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['phone']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Zipcode</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['zipcode']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Order No</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['order_no']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Address</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['address']); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Order Date</label>
                                    <div class="border p-1">
                                        <?php
                                        $datetime_parts = explode(' ', $data['created_at']);
                                        $date = $datetime_parts[0];
                                        $time = $datetime_parts[1];
                                        echo htmlspecialchars($date);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="fw-bold">Order Time</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($time); ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Notes</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['notes'] ?: 'N/A'); ?>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="fw-bold">Cancel Reason</label>
                                    <div class="border p-1">
                                        <?= htmlspecialchars($data['cancel_reason'] ?: 'N/A'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Order Details</h4>
                            <hr style="background-color: black">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Package Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $order_query = "SELECT o.id as oid, o.order_no, o.customer_id, oi.*, oi.quantity as order_quantity, f.* FROM orders o, order_items oi, food_items f WHERE oi.order_id=o.id AND f.id=oi.food_items_id AND o.order_no='$order_no'";
                                    $order_query_run = mysqli_query($con, $order_query);
                                    if (mysqli_num_rows($order_query_run) > 0) {
                                        foreach ($order_query_run as $item) {
                                            $item_total = $item['order_quantity'] * $item['price'];
                                            ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <img src="../Uploads/<?= htmlspecialchars($item['image']); ?>" width="50px" height="50px" alt="">
                                                    <?= htmlspecialchars($item['name']); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?= htmlspecialchars($item['price']); ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?= htmlspecialchars($item['order_quantity']); ?>
                                                </td>
                                                <td class="align-middle">
                                                    Rs <?= number_format($item_total); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <hr style="background-color: black">
                            <h5>Total Price: <span class="float-end fw-bold"><?= htmlspecialchars($data['total_price']); ?></span></h5>
                            <hr style="background-color: black">
                            <label class="fw-bold">Payment Mode</label>
                            <div class="border p-1 mb-3">
                                <?= htmlspecialchars($data['payment_mode']); ?>
                            </div>
                            <label class="fw-bold">Status</label>
                            <div class="mb-3">
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="order_no" value="<?= htmlspecialchars($data['order_no']); ?>">
                                    <select name="order_status" id="order_status" class="form-select">
                                        <?php
                                        $current_status = $data['status'];
                                        $statuses = [
                                            0 => 'Under Process',
                                            1 => 'Out for delivery',
                                            2 => 'Delivered',
                                            3 => 'Cancelled'
                                        ];
                                        // Ensure Cancelled is shown if current status is 3, otherwise exclude it if status >= 1
                                        foreach ($statuses as $value => $label) {
                                            if ($current_status == 3 && $value == 3) {
                                                // Always show Cancelled if the order is already cancelled
                                                echo "<option value='$value' selected>$label</option>";
                                            } elseif ($value >= $current_status && !($value == 3 && $current_status >= 1)) {
                                                // Show statuses >= current status, exclude Cancelled if status >= 1
                                                echo "<option value='$value' " . ($data['status'] == $value ? 'selected' : '') . ">$label</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div id="cancel_reason_div" style="display: <?= $data['status'] == 3 ? 'block' : 'none' ?>;">
                                        <label class="fw-bold mt-2">Cancellation Reason</label>
                                        <textarea name="cancel_reason" id="cancel_reason" class="form-control" placeholder="Enter reason for cancellation" <?= $data['status'] == 3 ? 'required' : '' ?>><?= htmlspecialchars($data['cancel_reason'] ?? ''); ?></textarea>
                                    </div>
                                    <button type="submit" name="update_order_btn" class="btn btn-primary mt-2">Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('order_status').addEventListener('change', function() {
        var reasonDiv = document.getElementById('cancel_reason_div');
        var reasonInput = document.getElementById('cancel_reason');
        if (this.value === '3') {
            reasonDiv.style.display = 'block';
            reasonInput.setAttribute('required', 'required');
        } else {
            reasonDiv.style.display = 'none';
            reasonInput.removeAttribute('required');
        }

        // Confirm status reversion
        var currentStatus = <?= json_encode($data['status']); ?>;
        if (this.value < currentStatus) {
            if (!confirm('Are you sure you want to revert to a previous status? This may affect order tracking.')) {
                this.value = currentStatus; // Revert selection
            }
        }
    });
</script>

<?php include('includes/footer.php'); ?>