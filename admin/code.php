<?php


include('../config/dbcon.php');
include('../functions/myfunctions.php');

//add users
if(isset($_POST['add_users_btn']))
{
    $name = mysqli_real_escape_string($con,$_POST['name']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $password = mysqli_real_escape_string($con,$_POST['password']);
    $cpassword = mysqli_real_escape_string($con,$_POST['cpassword']);
    $role_as = isset($_POST['role_as']) ? '1':'0' ;

    // Check if email already registered or not
    $check_email_query = "SELECT email FROM customer WHERE email='$email' ";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['message'] = "Email already registered";
        header('Location: add_user.php');
    }
    else
    {
        if($password == $cpassword)
        {
            $insert_query = "INSERT INTO customer (name,email,password,role_as) VALUES ('$name','$email','$password','$role_as')";
            $insert_query_run = mysqli_query($con, $insert_query);

            if($insert_query_run)
            {
            $_SESSION['message'] = "New User added Successfully";
            header('Location: add_user.php');
            }
            else
            {
                $_SESSION['message'] = "Something went wrong";
                header('Location: add_user.php');
            }
        }
        else
        {
            $_SESSION['message'] = "Password do not match";
            header('Location: add_user.php');
        }
    }
}

//update users
else if(isset($_POST['update_users_btn']))
{
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $role_as = isset($_POST['role_as']) ? '1':'0' ;

    
        if($password == $cpassword)
        {

            $hashedPassword = password_hash($password, PASSWORD_ARGON2I);
            $update_query = "UPDATE customer SET name='$name', email='$email',
            password='$hashedPassword', role_as='$role_as' WHERE id='$users_id' ";

            $update_query_run = mysqli_query($con, $update_query);

            if($update_query_run)
            {
                redirect("edit_users.php?id=$customer_id", "User Details Updated Successfully");
            }
            else
            {
                redirect("edit_users.php?id=$customer_id", "Something Went Wrong");
            }
        }
        else
        {
            redirect("edit_users.php?id=$customer_id", "Password do not match");
        }
    
}

//delete users
else if(isset($_POST['delete_users_btn']))
{
    $users_id = mysqli_real_escape_string($con, $_POST['users_id']);

    $users_query = "SELECT * FROM customer WHERE id='$users_id' ";
    $users_query_run = mysqli_query($con, $users_query);
    $users_data = mysqli_fetch_array($users_query_run);

    $delete_query = "DELETE FROM customer WHERE id='$users_id' ";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run)
    {
        //  redirect("users.php", "User data deleted Successfully");
        echo 300;
        
    }
    else
    {
        //  
        echo 500;
        
    }
}

// add menu
else if(isset($_POST['add_menu_btn']))
{
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? '1':'0' ;
    $popular = isset($_POST['popular']) ? '1':'0' ;

    $image = $_FILES['image']['name'];

    $path = "../uploads";

    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time().'.'.$image_ext;

    $check_name_query = "SELECT name FROM menu WHERE name='$name' ";
    $check_name_query_run = mysqli_query($con, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0)
    {
        $_SESSION['message'] = "Already in menu";
        header('Location: add_menu.php');
    }
    else
    {
        $pack_query = "INSERT INTO menu (name,slug,description,status,popular,image)
        VALUES ('$name','$slug','$description','$status','$popular','$filename')";

        $pack_query_run = mysqli_query($con, $pack_query);

        if($pack_query_run)
        {
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename);

            redirect("add_menu.php", "Menu Added Successfully");
        }
        else
        {
            redirect("add_menu.php", "Something Went Wrong");
        }
    }
}

// update menu
else if(isset($_POST['update_menu_btn']))
{
    $menu_id = $_POST['menu_id'];
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $status = isset($_POST['status']) ? '1':'0' ;
    $popular = isset($_POST['popular']) ? '1':'0' ;

    $new_image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];

    if($new_image != "")
    {
        // $update_filename = $new_image;
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time().'.'.$image_ext;
    }
    else
    {
        $update_filename = $old_image;
    }
    $path = "../uploads";

    $check_name_query = "SELECT name FROM menu WHERE name='$name' AND id != '$menu_id' ";
    $check_name_query_run = mysqli_query($con, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0)
    {
        $_SESSION['message'] = "Menu already there";
        header('Location: menu.php');
    }
    else
    {
        $update_query = "UPDATE menu SET name='$name', slug='$slug', description='$description',
        status='$status',popular='$popular',image='$update_filename' WHERE id='$menu_id' ";

        $update_query_run = mysqli_query($con, $update_query);

        if($update_query_run)
        {
            if($_FILES['image']['name'] != "")
            {
                move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
                if(file_exists("../uploads/".$old_image))
                {
                    unlink("../uploads/".$old_image);
                }
            }
            redirect("edit_menu.php?id=$menu_id", "Menu Updated Successfully");
        }
        else
        {
            redirect("edit_menu.php?id=$menu_id", "Something Went Wrong");
        }
    }
}

// delete menu
// else if(isset($_POST['delete_menu_btn']))
// {
//     $menu_id = mysqli_real_escape_string($con, $_POST['menu_id']);

//     $menu_query = "SELECT * FROM menu WHERE id='$menu_id' ";
//     $menu_query_run = mysqli_query($con, $menu_query);
//     $menu_data = mysqli_fetch_array($menu_query_run);
//     $image = $menu_data['image'];

//     $delete_query = "DELETE FROM menu WHERE id='$menu_id' ";
//     $delete_query_run = mysqli_query($con, $delete_query);

//     if($delete_query_run)
//     {
//         if(file_exists("../uploads/".$image))
//             {
//                 unlink("../uploads/".$image);
//             }
//         // redirect("classes.php", "Classes deleted Successfully");
//         echo 301;
//     }
//     else
//     {
//         // redirect("classes.php", "Something went wrong");
//         echo 500;
//     }
// }
if(isset($_POST['delete_menu_btn'])) {
    $menu_id = mysqli_real_escape_string($con, $_POST['menu_id']);
    echo "Deleting menu ID: $menu_id\n";

    $menu_query = "SELECT * FROM menu WHERE id='$menu_id'";
    $menu_query_run = mysqli_query($con, $menu_query);
    if (!$menu_query_run || mysqli_num_rows($menu_query_run) == 0) {
        echo "Menu not found or query failed";
        exit;
    }

    $menu_data = mysqli_fetch_array($menu_query_run);
    $image = $menu_data['image'];

    $delete_query = "DELETE FROM menu WHERE id='$menu_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run) {
        if(file_exists("../uploads/".$image)) {
            if (!unlink("../uploads/".$image)) {
                echo "Could not delete image: ".$image;
                exit;
            }
        }
        echo 301;
    } else {
        echo mysqli_error($con);
    }
}




// add food items

else if(isset($_POST['add_food_items_btn']))
{
    $menu_id = $_POST['menu_id'];

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1':'0' ;
    $trending = isset($_POST['trending']) ? '1':'0' ;

    $image = $_FILES['image']['name'];

    $path = "../uploads";

    $image_ext = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time().'.'.$image_ext;

    $check_name_query = "SELECT name FROM food_items WHERE name='$name' ";
    $check_name_query_run = mysqli_query($con, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0)
    {
        $_SESSION['message'] = "Food Name already there";
        header('Location: add_food_items.php');
    }
    else
    {
        if($name != "" && $slug != "" && $description != "")
        {
        
            $packages_query = "INSERT INTO food_items (menu_id,name,slug,description,price,quantity,status,trending,image) VALUES 
            ('$menu_id','$name','$slug','$description','$price','$quantity',
            '$status','$trending','$filename')";

            $packages_query_run = mysqli_query($con, $packages_query);

            if($packages_query_run)
            {
                move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$filename);

                redirect("add_food_items.php", "Food Added Successfully");
            }
            else
            {
                redirect("add_food_items.php", "Something went wrong");
            }
        }
        else
        {
            redirect("add_food_items.php", "All fields are mandetory");
        }   
    }
}

// update food items

else if(isset($_POST['update_food_items_btn']))
{
    $food_items_id = $_POST['food_items_id'];
    $menu_id = $_POST['menu_id'];

    $name = $_POST['name'];
    $slug = $_POST['slug'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $status = isset($_POST['status']) ? '1':'0' ;
    $trending = isset($_POST['trending']) ? '1':'0' ;

    $new_image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];

      if($new_image != "")
    {
        // $update_filename = $new_image;
        $image_ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time().'.'.$image_ext;
    }
    else
    {
        $update_filename = $old_image;
    }
    $path = "../uploads";

    $check_name_query = "SELECT name FROM food_items WHERE name='$name' AND id != '$food_items_id' ";
    $check_name_query_run = mysqli_query($con, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0)
    {
        $_SESSION['message'] = "Food already there";
        header('Location: menu_items.php');
    }
    else
    {
        $update_packages_query = "UPDATE food_items SET menu_id='$menu_id', name='$name', slug='$slug',
            description='$description', price='$price', image='$update_filename', quantity='$quantity',
            status='$status', trending='$trending' WHERE id='$food_items_id' ";


        $update_packages_query_run = mysqli_query($con, $update_packages_query);

        if($update_packages_query_run)
        {
            if($_FILES['image']['name'] != "")
            {
                move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$update_filename);
                if(file_exists("../uploads/".$old_image))
                {
                    unlink("../uploads/".$old_image);
                }
            }
            redirect("edit_food_items.php?id=$food_items_id", "Food Updated Successfully");
        }
        else
        {
            redirect("edit_food_items.php?id=$food_items_id", "Something Went Wrong");
        }
    }
}




//delete food items
else if(isset($_POST['delete_food_items_btn']))
{
    $food_items_id = mysqli_real_escape_string($con, $_POST['food_items_id']);

    $food_items_query = "SELECT * FROM food_items WHERE id='$food_items_id' ";
    $food_items_query_run = mysqli_query($con, $food_items_query);
    $food_items_data = mysqli_fetch_array($food_items_query_run);
    $image = $food_items_data['image'];

    $delete_query = "DELETE FROM food_items WHERE id='$food_items_id' ";
    $delete_query_run = mysqli_query($con, $delete_query);

    if($delete_query_run)
    {
        if(file_exists("../uploads/".$image))
            {
                unlink("../uploads/".$image);
            }
        // redirect("food_items.php", "Food Items deleted Successfully");
        echo 300;
    }
    else
    {
        // redirect("food_items.php", "Something went wrong");
        echo 500;
    }
}



//delete orders
else if(isset($_POST['delete_orders_btn']))
{
     $orders_id = mysqli_real_escape_string($con, $_POST['orders_id']);

     $orders_query = "SELECT * FROM orders WHERE id='$orders_id' ";
     $orders_query_run = mysqli_query($con, $orders_query);
     $orders_data = mysqli_fetch_array($orders_query_run);

    $delete_query = "DELETE FROM orders WHERE id='$orders_id' ";
     $delete_query_run = mysqli_query($con, $delete_query);
     
    if($delete_query_run)
    {
         // redirect("packages.php", "Packages deleted Successfully");
        echo 302;
     }
    else
    {
         // redirect("packages.php", "Something went wrong");
         echo 500;
     }
}

//update orders
if (isset($_POST['update_order_btn'])) {
    $order_no = mysqli_real_escape_string($con, $_POST['order_no']);
    $order_status = mysqli_real_escape_string($con, $_POST['order_status']);
    $cancel_reason = isset($_POST['cancel_reason']) ? mysqli_real_escape_string($con, $_POST['cancel_reason']) : NULL;

    // Validate order status (must be 0, 1, 2, or 3)
    if (!in_array($order_status, ['0', '1', '2', '3'])) {
        redirect("view_order.php?o=$order_no", "Invalid status value");
        exit();
    }

    // Check if order exists
    $order_query = "SELECT id, status FROM orders WHERE order_no='$order_no'";
    $order_result = mysqli_query($con, $order_query);

    if (mysqli_num_rows($order_result) > 0) {
        $order_data = mysqli_fetch_array($order_result);
        $current_status = $order_data['status'];
        $order_id = $order_data['id'];

        // Prevent reversion to lower status
        if ($order_status < $current_status) {
            redirect("view_order.php?o=$order_no", "Cannot revert to a previous status");
            exit();
        }

        // If cancelling, reason is required
        if ($order_status == '3' && empty($cancel_reason)) {
            redirect("view_order.php?o=$order_no", "Cancellation reason is required");
            exit();
        }

        // If cancelling and not already cancelled
        if ($order_status == '3' && $current_status != '3') {
            // Fetch order items
            $items_query = "SELECT food_items_id, quantity FROM order_items WHERE order_id='$order_id'";
            $items_result = mysqli_query($con, $items_query);

            if (mysqli_num_rows($items_result) > 0) {
                while ($item = mysqli_fetch_assoc($items_result)) {
                    $food_item_id = $item['food_items_id'];
                    $ordered_qty = $item['quantity'];

                    // Update stock in food_items
                    $update_stock_query = "UPDATE food_items SET quantity = quantity + $ordered_qty WHERE id = '$food_item_id'";
                    mysqli_query($con, $update_stock_query);
                }
            }
        }

        // Build update query
        $update_query = "UPDATE orders SET status='$order_status'";
        if ($order_status == '3') {
            $update_query .= ", cancel_reason='$cancel_reason'";
        } else {
            $update_query .= ", cancel_reason=NULL";
        }
        $update_query .= " WHERE order_no='$order_no'";

        // Execute update
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            redirect("view_order.php?o=$order_no", "Order status updated successfully");
        } else {
            error_log("Order update failed: " . mysqli_error($con), 3, "/path/to/error.log");
            redirect("view_order.php?o=$order_no", "Failed to update order status: " . mysqli_error($con));
        }
    } else {
        redirect("view_order.php?o=$order_no", "Invalid order number");
    }
}




?>