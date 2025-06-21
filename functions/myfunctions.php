<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config/dbcon.php');

if (!function_exists('getAll')) {
    function getAll($table) {
        global $con;
        $query = "SELECT * FROM $table";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getJoin')) {
    function getJoin($table, $search = '', $sort = 'id', $order = 'ASC') {
        global $con;
        $valid_sort_columns = ['id', 'name', 'menu_name', 'price', 'quantity', 'status'];
        $sort = in_array($sort, $valid_sort_columns) ? $sort : 'id';
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $where_clause = "";
        if (!empty($search)) {
            $search = mysqli_real_escape_string($con, $search);
            $where_clause = "WHERE food_items.name LIKE '%$search%' OR menu.name LIKE '%$search%'";
        }
        $query = "SELECT food_items.*, menu.name AS menu_name 
                  FROM food_items 
                  JOIN menu ON food_items.menu_id = menu.id 
                  $where_clause 
                  ORDER BY $sort $order";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getByID')) {
    function getByID($table, $id) {
        global $con;
        $query = "SELECT * FROM $table WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getByID prepare failed: ' . mysqli_error($con));
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getByID execute failed: ' . mysqli_error($con));
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getByID result failed: ' . mysqli_error($con));
            return false;
        }
        return $result;
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $message) {
        $_SESSION['message'] = $message;
        header('Location: ' . $url);
        exit();
    }
}

if (!function_exists('getPendingOrders')) {
    function getPendingOrders() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status = 0 ";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getOrderHistory')) {
    function getOrderHistory() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status IN ('2', '3')";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getDelivered')) {
    function getDelivered() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status = 2 ";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getOutForDelivery')) {
    function getOutForDelivery() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status = 1 ";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getCancelled')) {
    function getCancelled() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status = 3 ";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('getAllOrders')) {
    function getAllOrders() {
        global $con;
        $query = "SELECT o.*, c.name FROM orders o JOIN customer c ON o.customer_id = c.id WHERE o.status IN ('0', '1')";
        return mysqli_query($con, $query);
    }
}

if (!function_exists('checkOrderNoValid')) {
    function checkOrderNoValid($orderNo) {
        global $con;
        $query = "SELECT * FROM orders WHERE order_no = ?";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('checkOrderNoValid prepare failed: ' . mysqli_error($con));
            return false;
        }
        mysqli_stmt_bind_param($stmt, 's', $orderNo);
        if (!mysqli_stmt_execute($stmt)) {
            error_log('checkOrderNoValid execute failed: ' . mysqli_error($con));
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }
}

date_default_timezone_set('Asia/Kathmandu');

if (!function_exists('getTotalSales')) {
    function getTotalSales($param1, $filter_type, $param3, $param4, $param5, $start_date, $end_date) {
        global $con;
        $query = "SELECT 
                    DATE(created_at) AS period, 
                    SUM(total_price) AS total_sales, 
                    COUNT(id) AS order_count 
                  FROM orders 
                  WHERE status = 2";
        $params = [];
        $types = '';

        if (!empty($start_date) && !empty($end_date)) {
            $end_date_param = $end_date . ' 23:59:59';
            $query .= " AND created_at BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date_param;
            $types .= 'ss';
        }

        $query .= " GROUP BY DATE(created_at) ORDER BY period";

        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalSales prepare failed: ' . mysqli_error($con));
            return false;
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalSales execute failed: ' . mysqli_error($con));
            return false;
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalSales result failed: ' . mysqli_error($con));
            return false;
        }

        $row_count = mysqli_num_rows($result);
        error_log("getTotalSales: Query = $query, Start = $start_date, End = $end_date, Rows = $row_count");
        return $result;
    }
}

if (!function_exists('getPopularMenuItems')) {
    function getPopularMenuItems($start_date, $end_date) {
        global $con;
        $query = "SELECT 
                    fi.name AS item_name, 
                    COALESCE(SUM(oi.quantity), 0) AS quantity_sold, 
                    COALESCE(SUM(oi.quantity * oi.price), 0) AS total_revenue 
                  FROM order_items oi 
                  JOIN orders o ON oi.order_id = o.id 
                  JOIN food_items fi ON oi.food_items_id = fi.id 
                  WHERE o.status = 2";
        $params = [];
        $types = '';

        if (!empty($start_date) && !empty($end_date)) {
            $end_date_param = $end_date . ' 23:59:59';
            $query .= " AND o.created_at BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date_param;
            $types .= 'ss';
        }

        $query .= " GROUP BY fi.name ORDER BY quantity_sold DESC LIMIT 10";

        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getPopularMenuItems prepare failed: ' . mysqli_error($con));
            return false;
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            error_log('getPopularMenuItems execute failed: ' . mysqli_error($con));
            return false;
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getPopularMenuItems result failed: ' . mysqli_error($con));
            return false;
        }

        $row_count = mysqli_num_rows($result);
        error_log("getPopularMenuItems: Query = $query, Start = $start_date, End = $end_date, Rows = $row_count");
        return $result;
    }
}

if (!function_exists('getOrderTimes')) {
    function getOrderTimes($start_date, $end_date) {
        global $con;
        $query = "SELECT 
                    HOUR(created_at) AS hour, 
                    COUNT(id) AS order_count 
                  FROM orders 
                  WHERE status = 2";
        $params = [];
        $types = '';

        if (!empty($start_date) && !empty($end_date)) {
            $end_date_param = $end_date . ' 23:59:59';
            $query .= " AND created_at BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date_param;
            $types .= 'ss';
        }

        $query .= " GROUP BY HOUR(created_at) ORDER BY hour";

        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getOrderTimes prepare failed: ' . mysqli_error($con));
            return false;
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            error_log('getOrderTimes execute failed: ' . mysqli_error($con));
            return false;
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getOrderTimes result failed: ' . mysqli_error($con));
            return false;
        }

        $row_count = mysqli_num_rows($result);
        error_log("getOrderTimes: Query = $query, Start = $start_date, End = $end_date, Rows = $row_count");
        return $result;
    }
}

if (!function_exists('getTotalUsers')) {
    function getTotalUsers() {
        global $con;
        $query = "SELECT COUNT(id) AS total_users FROM customer";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalUsers prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalUsers execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalUsers result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['total_users'] ?? 0;
    }
}

if (!function_exists('getTotalMenus')) {
    function getTotalMenus() {
        global $con;
        $query = "SELECT COUNT(id) AS total_menus FROM menu";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalMenus prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalMenus execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalMenus result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['total_menus'] ?? 0;
    }
}

if (!function_exists('getTotalFoodItems')) {
    function getTotalFoodItems() {
        global $con;
        $query = "SELECT COUNT(id) AS total_food_items FROM food_items";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalFoodItems prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalFoodItems execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalFoodItems result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['total_food_items'] ?? 0;
    }
}

if (!function_exists('getTotalOrders')) {
    function getTotalOrders() {
        global $con;
        $query = "SELECT COUNT(id) AS total_orders 
                  FROM orders 
                  WHERE status = 2";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalOrders prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalOrders execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalOrders result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['total_orders'] ?? 0;
    }
}

if (!function_exists('getTotalRevenue')) {
    function getTotalRevenue() {
        global $con;
        $query = "SELECT SUM(total_price) AS total_revenue 
                  FROM orders 
                  WHERE status = 2";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTotalRevenue prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTotalRevenue execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTotalRevenue result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['total_revenue'] ?? 0;
    }
}

if (!function_exists('getTotalPendingOrders')) {
    function getTotalPendingOrders() {
        global $con;
        $query = "SELECT COUNT(id) AS pending_orders 
                  FROM orders 
                  WHERE status IN ('0', '1')";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getPendingOrders prepare failed: ' . mysqli_error($con));
            return 0;
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getPendingOrders execute failed: ' . mysqli_error($con));
            return 0;
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getPendingOrders result failed: ' . mysqli_error($con));
            return 0;
        }
        $row = mysqli_fetch_assoc($result);
        return $row['pending_orders'] ?? 0;
    }
}

if (!function_exists('getTopSellingItem')) {
    function getTopSellingItem() {
        global $con;
        $query = "SELECT fi.name AS item_name, SUM(oi.quantity) AS quantity_sold 
                  FROM order_items oi 
                  JOIN orders o ON oi.order_id = o.id 
                  JOIN food_items fi ON oi.food_items_id = fi.id 
                  WHERE o.status = 2 
                  GROUP BY fi.name 
                  ORDER BY quantity_sold DESC 
                  LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) {
            error_log('getTopSellingItem prepare failed: ' . mysqli_error($con));
            return ['name' => 'None', 'quantity' => 0];
        }
        if (!mysqli_stmt_execute($stmt)) {
            error_log('getTopSellingItem execute failed: ' . mysqli_error($con));
            return ['name' => 'None', 'quantity' => 0];
        }
        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            error_log('getTopSellingItem result failed: ' . mysqli_error($con));
            return ['name' => 'None', 'quantity' => 0];
        }
        $row = mysqli_fetch_assoc($result);
        return $row ? ['name' => $row['item_name'], 'quantity' => $row['quantity_sold']] : ['name' => 'None', 'quantity' => 0];
    }
}
?>