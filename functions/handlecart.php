<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config/dbcon.php');

header('Content-Type: application/json');

$response = ['status' => 500, 'message' => 'Something went wrong'];

if (!isset($_SESSION['auth'])) {
    $response['status'] = 401;
    $response['message'] = 'Login to continue';
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['scope'])) {
    echo json_encode($response);
    exit;
}

$customer_id = $_SESSION['auth_user']['user_id'];
$scope = $_POST['scope'];

switch ($scope) {
    case "add":
        $food_items_id = $_POST['food_items_id'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($quantity < 1 || empty($food_items_id)) {
            $response['message'] = 'Invalid input';
            echo json_encode($response);
            exit;
        }

        // Check stock
        $stock_stmt = $con->prepare("SELECT quantity FROM food_items WHERE id = ?");
        $stock_stmt->bind_param("i", $food_items_id);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        if ($stock_result->num_rows > 0) {
            $available_quantity = $stock_result->fetch_assoc()['quantity'];
            if ($quantity > $available_quantity) {
                $response['message'] = 'Requested quantity exceeds available stock (' . $available_quantity . ')';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'Food item not found';
            echo json_encode($response);
            exit;
        }

        // Check if item exists in cart
        $chk_stmt = $con->prepare("SELECT * FROM carts WHERE food_items_id = ? AND customer_id = ?");
        $chk_stmt->bind_param("ii", $food_items_id, $customer_id);
        $chk_stmt->execute();
        $chk_result = $chk_stmt->get_result();

        if ($chk_result->num_rows > 0) {
            $response['status'] = 'existing';
            $response['message'] = 'Food already in cart';
        } else {
            $insert_stmt = $con->prepare("INSERT INTO carts (customer_id, food_items_id, quantity) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iii", $customer_id, $food_items_id, $quantity);
            if ($insert_stmt->execute()) {
                $response['status'] = 201;
                $response['message'] = 'Food added to cart';
            }
        }
        break;

    case "update":
        $food_items_id = $_POST['food_items_id'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($quantity < 1 || empty($food_items_id)) {
            $response['message'] = 'Invalid input';
            echo json_encode($response);
            exit;
        }

        // Check stock
        $stock_stmt = $con->prepare("SELECT quantity FROM food_items WHERE id = ?");
        $stock_stmt->bind_param("i", $food_items_id);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        if ($stock_result->num_rows > 0) {
            $available_quantity = $stock_result->fetch_assoc()['quantity'];
            if ($quantity > $available_quantity) {
                $response['message'] = 'Requested quantity exceeds available stock (' . $available_quantity . ')';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = 'Food item not found';
            echo json_encode($response);
            exit;
        }

        // Check if item exists in cart
        $chk_stmt = $con->prepare("SELECT * FROM carts WHERE food_items_id = ? AND customer_id = ?");
        $chk_stmt->bind_param("ii", $food_items_id, $customer_id);
        $chk_stmt->execute();
        $chk_result = $chk_stmt->get_result();

        if ($chk_result->num_rows > 0) {
            $update_stmt = $con->prepare("UPDATE carts SET quantity = ? WHERE food_items_id = ? AND customer_id = ?");
            $update_stmt->bind_param("iii", $quantity, $food_items_id, $customer_id);
            if ($update_stmt->execute()) {
                $response['status'] = 200;
                $response['message'] = 'Quantity updated successfully';
            }
        } else {
            $response['message'] = 'Item not found in cart';
        }
        break;

    case "delete":
        $cart_id = $_POST['cart_id'] ?? '';

        if (empty($cart_id)) {
            $response['message'] = 'Invalid cart ID';
            echo json_encode($response);
            exit;
        }

        // Check if cart item exists
        $chk_stmt = $con->prepare("SELECT * FROM carts WHERE id = ? AND customer_id = ?");
        $chk_stmt->bind_param("ii", $cart_id, $customer_id);
        $chk_stmt->execute();
        $chk_result = $chk_stmt->get_result();

        if ($chk_result->num_rows > 0) {
            $delete_stmt = $con->prepare("DELETE FROM carts WHERE id = ? AND customer_id = ?");
            $delete_stmt->bind_param("ii", $cart_id, $customer_id);
            if ($delete_stmt->execute()) {
                $response['status'] = 200;
                $response['message'] = 'Cart item deleted successfully';
            }
        } else {
            $response['message'] = 'Cart item not found';
        }
        break;

    default:
        $response['message'] = 'Invalid scope';
        break;
}

echo json_encode($response);
?>