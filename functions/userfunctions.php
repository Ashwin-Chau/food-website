<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config/dbcon.php');

function redirect($url, $message)
{
    $_SESSION['message'] = $message;
     header('Location: '.$url);
     exit();
}

function getMenuItem($table)
{
    global $con;
    $query = "SELECT * FROM $table WHERE status='0'";
    return $query_run = mysqli_query($con, $query);
}

function getAllTrending()
{
    global $con;
    $query = "SELECT * FROM food_items WHERE trending='1' AND status='0' ";
    return $query_run = mysqli_query($con, $query);
}


function getSlugActive($table, $slug)
{
    global $con;
    $query = "SELECT * FROM $table WHERE slug='$slug' AND status='0' LIMIT 1";
    return $query_run = mysqli_query($con, $query);
}


function getPackByClasses($classes_id)
{
    global $con;
    $query = "SELECT * FROM packages WHERE classes_id='$classes_id' AND status='0' ";
    return $query_run = mysqli_query($con, $query);
}


function getFoodByMenu($menu_id)
{
    global $con;
    $query = "SELECT * FROM food_items WHERE menu_id='$menu_id' AND status='0' ";
    return $query_run = mysqli_query($con, $query);
}


function getAllActiveFood()
{
    global $con;
    $query = "SELECT * FROM food_items WHERE status = 0 ORDER BY id DESC";
    $result = mysqli_query($con, $query);
    $data = array();
    while($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data; // Returns an array of associative arrays
}

function searchFoodItems($query)
{
    global $con;
    // Escape the query to prevent SQL injection
    $query = mysqli_real_escape_string($con, $query);
    // Search for items where name or description contains the query (case-insensitive)
    $sql = "SELECT * FROM food_items 
            WHERE status = 0 
            AND (name LIKE '%$query%' OR description LIKE '%$query%')
            ORDER BY id DESC";
    $result = mysqli_query($con, $sql);
    $data = array();
    while($row = mysqli_fetch_assoc($result))
    {
        $data[] = $row;
    }
    return $data; // Returns an array of matching food items
}


function getIDActive($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id='$id' AND status='0' ";
    return $query_run = mysqli_query($con, $query);
}

function getCartItems() {
    global $con;
    $user_id = $_SESSION['auth_user']['user_id'];
    $query = "SELECT c.id as cid, c.food_items_id, c.quantity, f.name, f.price, f.quantity as available_quantity 
              FROM carts c 
              JOIN food_items f ON c.food_items_id = f.id 
              WHERE c.customer_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch all orders
function getAllOrders()
{
    global $con;
    $query = "SELECT * FROM orders ORDER BY created_at ASC";
    return mysqli_query($con, $query);
}

// Update order status
function updateOrderStatus($order_id, $status, $cancel_reason = null)
{
    global $con;
    $order_id = mysqli_real_escape_string($con, $order_id);
    $status = mysqli_real_escape_string($con, $status);
    
    $query = "UPDATE orders SET status = '$status'";
    if ($status == '3' && $cancel_reason !== null) {
        $cancel_reason = mysqli_real_escape_string($con, $cancel_reason);
        $query .= ", cancel_reason = '$cancel_reason'";
    }
    $query .= " WHERE id = '$order_id'";
    
    return mysqli_query($con, $query);
}

function checkOrderNoValid($orderNo)
{
    global $con;
    $customer_id = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM orders WHERE order_no='$orderNo' AND customer_id='$customer_id' ";
    return mysqli_query($con, $query);
}

function getOrders()
{
    global $con;
    $customer_id = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY id DESC ";
    return $query_run = mysqli_query($con, $query);
}

function getUsers()
{
    global $con;
    $userId = $_SESSION['auth_user']['user_id'];

    $query = "SELECT * FROM users WHERE user_id='$userId' ";
    return $query_run = mysqli_query($con, $query);
}

function getCustomerDetails($con) {
    // Check if session variable exists
    if (!isset($_SESSION['auth_user']['user_id'])) {
        return false; // No logged-in user
    }

    $customer_id = $_SESSION['auth_user']['user_id'];
    
    // Prepare the SQL query
    $query = "SELECT name, email 
              FROM customer 
              WHERE id = ?";
    
    // Initialize prepared statement
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        return false; // Handle error
    }

    // Bind the customer_id parameter
    mysqli_stmt_bind_param($stmt, "i", $customer_id); // Assuming id is an integer
    
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Get the result
    $result = mysqli_stmt_get_result($stmt);
    
    // Fetch the customer data
    $customer = mysqli_fetch_assoc($result);
    
    // Close the statement
    mysqli_stmt_close($stmt);
    
    return $customer ?: false; // Return customer data or false if no record found
}






?>