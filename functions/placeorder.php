<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config/dbcon.php');

if (isset($_SESSION['auth'])) {
    if (isset($_POST['placeOrderBtn'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $phone = mysqli_real_escape_string($con, $_POST['phone']);
        $zipcode = mysqli_real_escape_string($con, $_POST['zipcode']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $payment_mode = mysqli_real_escape_string($con, $_POST['payment_mode']);
        $payment_id = mysqli_real_escape_string($con, $_POST['payment_id']);
        $notes = isset($_POST['notes']) ? mysqli_real_escape_string($con, $_POST['notes']) : NULL;

        if ($name == "" || $email == "" || $phone == "" || $zipcode == "" || $address == "") {
            $_SESSION['message'] = "All fields are mandatory";
            header('Location: ../checkout.php');
            exit(0);
        }

        $customer_id = $_SESSION['auth_user']['user_id'];
        $query = "SELECT c.id as cid, c.food_items_id, c.quantity, f.id as fid, f.name, f.price
            FROM carts c, food_items f WHERE c.food_items_id=f.id AND c.customer_id='$customer_id' ORDER BY c.id DESC";
        $query_run = mysqli_query($con, $query);

        $totalPrice = 0;
        foreach ($query_run as $citem) {
            $totalPrice += $citem['price'] * $citem['quantity'];
        }

        $order_no = "foodhub" . rand(11, 99) . substr($phone, 2);
        $insert_query = "INSERT INTO orders (order_no, customer_id, name, email, phone, zipcode, address, total_price, payment_mode, payment_id, notes) VALUES
            ('$order_no', '$customer_id', '$name', '$email', '$phone', '$zipcode', '$address', '$totalPrice', '$payment_mode', '$payment_id', '$notes')";
        $insert_query_run = mysqli_query($con, $insert_query);

        if ($insert_query_run) {
            $order_id = mysqli_insert_id($con);
            foreach ($query_run as $citem) {
                $food_items_id = $citem['food_items_id'];
                $quantity = $citem['quantity'];
                $price = $citem['price'];
                $insert_items_query = "INSERT INTO order_items (order_id, food_items_id, quantity, price) VALUES ('$order_id', '$food_items_id', '$quantity', '$price')";
                $insert_items_query_run = mysqli_query($con, $insert_items_query);

                $food_items_query = "SELECT * FROM food_items WHERE id='$food_items_id' LIMIT 1";
                $food_items_query_run = mysqli_query($con, $food_items_query);
                $food_itemsData = mysqli_fetch_array($food_items_query_run);
                $current_quantity = $food_itemsData['quantity'];
                $new_quantity = $current_quantity - $quantity;
                $update_quantity_query = "UPDATE food_items SET quantity='$new_quantity' WHERE id='$food_items_id'";
                $update_quantity_query_run = mysqli_query($con, $update_quantity_query);
            }

            $deleteCartQuery = "DELETE FROM carts WHERE customer_id='$customer_id'";
            $deleteCartQuery_run = mysqli_query($con, $deleteCartQuery);

            if($payment_mode == "COD")
            {
                $_SESSION['message'] = "Order placed successfully";
                header('Location: ../my_orders.php');
                die();
            } else{
                echo 201;
            }
        }
    }
} else {
    header('Location: ../index.php');
}
?>