<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../config/dbcon.php');
include('myfunctions.php');

require_once __DIR__ . '/../init.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// User registration
if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    if ($password == $cpassword) {
        // Generate OTP
        $otp = rand(100000, 999999);

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_ARGON2I);

        // Check if email already registered
        $check_email_query = $con->prepare("SELECT email, status FROM customer WHERE email = ?");
        $check_email_query->bind_param("s", $email);
        $check_email_query->execute();
        $check_email_query_run = $check_email_query->get_result();

        if ($check_email_query_run->num_rows > 0) {
            $row = $check_email_query_run->fetch_assoc();
            if ($row['status'] == 1) {
                redirect("../register.php", "Email already registered and verified");
            } else {
                // Email exists but is unverified; update with new OTP
                $to = $email;
                $subject = 'Your OTP Code';
                $message = "Your OTP code for registration is: <b>$otp</b>. Please use this to verify your account.";
                $headers = "From: your_email@gmail.com\r\n";
                $headers .= "Reply-To: your_email@gmail.com\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    $update_query = $con->prepare("UPDATE customer SET name = ?, password = ?, otp = ? WHERE email = ?");
                    $update_query->bind_param("ssss", $name, $hashedPassword, $otp, $email);
                    if ($update_query->execute()) {
                        $_SESSION['verify_email'] = $email;
                        $_SESSION['otp_type'] = 'register'; // Track OTP purpose
                        redirect("../verify_otp.php", "OTP sent to your email");
                    } else {
                        redirect("../register.php", "Failed to update user data");
                    }
                } else {
                    error_log("Failed to send OTP to $email at " . date('Y-m-d H:i:s') . ". SMTP: " . ini_get('SMTP') . ", Port: " . ini_get('smtp_port'));
                    redirect("../register.php", "Failed to send OTP. Please try again.");
                }
            }
        } else {
            // New email; insert after sending OTP
            $to = $email;
            $subject = 'Your OTP Code';
            $message = "Your OTP code for registration is: <b>$otp</b>. Please use this to verify your account.";
            $headers = "From: your_email@gmail.com\r\n";
            $headers .= "Reply-To: your_email@gmail.com\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($to, $subject, $message, $headers)) {
                $insert_query = $con->prepare("INSERT INTO customer (name, email, password, otp, status) VALUES (?, ?, ?, ?, 0)");
                $insert_query->bind_param("ssss", $name, $email, $hashedPassword, $otp);
                if ($insert_query->execute()) {
                    $_SESSION['verify_email'] = $email;
                    $_SESSION['otp_type'] = 'register'; // Track OTP purpose
                    redirect("../verify_otp.php", "OTP sent to your email");
                } else {
                    redirect("../register.php", "Failed to register user");
                }
            } else {
                error_log("Failed to send OTP to $email at " . date('Y-m-d H:i:s') . ". SMTP: " . ini_get('SMTP') . ", Port: " . ini_get('smtp_port'));
                redirect("../register.php", "Failed to send OTP. Please try again.");
            }
        }
    } else {
        redirect("../register.php", "Passwords do not match");
    }
}

// Forgot password
elseif (isset($_POST['forgot_password_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email exists and is verified
    $check_email_query = $con->prepare("SELECT email, status FROM customer WHERE email = ?");
    $check_email_query->bind_param("s", $email);
    $check_email_query->execute();
    $check_email_query_run = $check_email_query->get_result();

    if ($check_email_query_run->num_rows > 0) {
        $row = $check_email_query_run->fetch_assoc();
        if ($row['status'] == 0) {
            redirect("../forgot_password.php", "Account not verified. Please register and verify.");
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Send OTP email
            $to = $email;
            $subject = 'Password Reset OTP';
            $message = "Your OTP code for password reset is: <b>$otp</b>. Please use this to reset your password.";
            $headers = "From: your_email@gmail.com\r\n";
            $headers .= "Reply-To: your_email@gmail.com\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($to, $subject, $message, $headers)) {
                // Update OTP in database
                $update_query = $con->prepare("UPDATE customer SET otp = ? WHERE email = ?");
                $update_query->bind_param("ss", $otp, $email);
                if ($update_query->execute()) {
                    $_SESSION['verify_email'] = $email;
                    $_SESSION['otp_type'] = 'reset'; // Track OTP purpose
                    redirect("../verify_otp.php", "OTP sent to your email");
                } else {
                    redirect("../forgot_password.php", "Failed to update OTP");
                }
            } else {
                error_log("Failed to send OTP to $email at " . date('Y-m-d H:i:s') . ". SMTP: " . ini_get('SMTP') . ", Port: " . ini_get('smtp_port'));
                redirect("../forgot_password.php", "Failed to send OTP. Please try again.");
            }
        }
    } else {
        redirect("../forgot_password.php", "Email not registered");
    }
}

// OTP verification
elseif (isset($_POST['verify_otp_btn'])) {
    $email = mysqli_real_escape_string($con, $_SESSION['verify_email']);
    $otp = mysqli_real_escape_string($con, $_POST['otp']);
    $otp_type = $_SESSION['otp_type'] ?? 'register';

    $verify_query = $con->prepare("SELECT otp FROM customer WHERE email = ? AND otp = ?");
    $verify_query->bind_param("ss", $email, $otp);
    $verify_query->execute();
    $verify_query_run = $verify_query->get_result();

    if ($verify_query_run->num_rows > 0) {
        if ($otp_type === 'register') {
            // Update status to 1 and clear OTP for registration
            $update_query = $con->prepare("UPDATE customer SET status = 1, otp = NULL WHERE email = ?");
            $update_query->bind_param("s", $email);
            if ($update_query->execute()) {
                unset($_SESSION['otp_type']);
                redirect("../login.php", "Registration Successful. Please login.");
            } else {
                redirect("../verify_otp.php", "Something went wrong");
            }
        } else {
            // For password reset, redirect to reset password page
            $update_query = $con->prepare("UPDATE customer SET otp = NULL WHERE email = ?");
            $update_query->bind_param("s", $email);
            if ($update_query->execute()) {
                $_SESSION['reset_email'] = $email;
                unset($_SESSION['otp_type']);
                redirect("../reset_password.php", "OTP verified. Set your new password.");
            } else {
                redirect("../verify_otp.php", "Something went wrong");
            }
        }
    } else {
        redirect("../verify_otp.php", "Invalid OTP");
    }
}

// Resend OTP
elseif (isset($_GET['resend_otp'])) {
    if (!isset($_SESSION['verify_email'])) {
        redirect("../verify_otp.php", "No email found for OTP verification.");
        exit();
    }

    // Rate-limiting: Check if last resend was within 30 seconds
    if (isset($_SESSION['last_otp_resend']) && (time() - $_SESSION['last_otp_resend']) < 30) {
        redirect("../verify_otp.php", "Please wait 30 seconds before resending OTP.");
        exit();
    }

    $email = mysqli_real_escape_string($con, $_SESSION['verify_email']);
    $otp_type = $_SESSION['otp_type'] ?? 'register';

    // Generate new OTP
    $new_otp = rand(100000, 999999);

    // Update OTP in database
    $update_query = $con->prepare("UPDATE customer SET otp = ? WHERE email = ?");
    $update_query->bind_param("ss", $new_otp, $email);

    if ($update_query->execute()) {
        // Send new OTP email
        $subject = ($otp_type === 'register') ? 'Your OTP Code' : 'Password Reset OTP';
        $message = "Your new OTP code for " . ($otp_type === 'register' ? 'registration' : 'password reset') . " is: <b>$new_otp</b>. Please use this to verify your account.";
        $headers = "From: your_email@gmail.com\r\n";
        $headers .= "Reply-To: your_email@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['last_otp_resend'] = time(); // Store resend timestamp
            redirect("../verify_otp.php", "New OTP sent to your email.");
        } else {
            error_log("Failed to send OTP to $email at " . date('Y-m-d H:i:s') . ". SMTP: " . ini_get('SMTP') . ", Port: " . ini_get('smtp_port'));
            redirect("../verify_otp.php", "Failed to send new OTP. Please try again.");
        }
    } else {
        redirect("../verify_otp.php", "Something went wrong while generating new OTP.");
    }
}

// Reset password
elseif (isset($_POST['reset_password_btn'])) {
    $email = mysqli_real_escape_string($con, $_SESSION['reset_email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    if ($password == $cpassword) {
        $hashedPassword = password_hash($password, PASSWORD_ARGON2I);
        $update_query = $con->prepare("UPDATE customer SET password = ? WHERE email = ?");
        $update_query->bind_param("ss", $hashedPassword, $email);
        if ($update_query->execute()) {
            unset($_SESSION['reset_email']);
            redirect("../login.php", "Password reset successful. Please login.");
        } else {
            redirect("../reset_password.php", "Failed to reset password");
        }
    } else {
        redirect("../reset_password.php", "Passwords do not match");
    }
}

// Change password
elseif (isset($_POST['change_password_btn'])) {
    $email = mysqli_real_escape_string($con, $_SESSION['auth_user']['email']);
    $current_password = mysqli_real_escape_string($con, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Fetch current password hash
    $query = $con->prepare("SELECT password FROM customer WHERE email = ? AND status = 1");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($current_password, $user['password'])) {
            if ($new_password == $confirm_password) {
                $hashedPassword = password_hash($new_password, PASSWORD_ARGON2I);
                $update_query = $con->prepare("UPDATE customer SET password = ? WHERE email = ?");
                $update_query->bind_param("ss", $hashedPassword, $email);
                if ($update_query->execute()) {
                    // Destroy session and redirect to login
                    session_destroy();
                    redirect("../login.php", "Password changed successfully. Please login with your new password.");
                } else {
                    redirect("../change_password.php", "Failed to change password");
                }
            } else {
                redirect("../change_password.php", "New passwords do not match");
            }
        } else {
            redirect("../change_password.php", "Current password is incorrect");
        }
    } else {
        redirect("../change_password.php", "User not found");
    }
}

// User login
elseif (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $login_query = $con->prepare("SELECT * FROM customer WHERE email = ? AND status = 1");
    $login_query->bind_param("s", $email);
    $login_query->execute();
    $login_query_run = $login_query->get_result();

    if ($login_query_run->num_rows > 0) {
        $userdata = $login_query_run->fetch_assoc();
        if (password_verify($password, $userdata['password'])) {
            $_SESSION['auth'] = true;

            $userid = $userdata['id'];
            $username = $userdata['name'];
            $useremail = $userdata['email'];
            $role_as = $userdata['role_as'];

            $_SESSION['auth_user'] = [
                'user_id' => $userid,
                'name' => $username,
                'email' => $useremail
            ];

            $_SESSION['role_as'] = $role_as;

            if ($role_as == 1) {
                redirect("../admin/index.php", "Welcome to Admin Dashboard");
            } else {
                redirect("../index.php", "Logged In Successfully");
            }
        } else {
            redirect("../login.php", "Password does not match");
        }
    } else {
        redirect("../login.php", "Invalid email or account not verified");
    }
}

// Cancel order
elseif (isset($_POST['order_id'], $_POST['cancel_reason'])) {
    // Ensure user is authenticated
    if (!isset($_SESSION['auth_user']['user_id'])) {
        redirect("../login.php", "Please log in to cancel an order");
        exit;
    }

    $order_id = mysqli_real_escape_string($con, $_POST['order_id']);
    $cancel_reason = mysqli_real_escape_string($con, $_POST['cancel_reason']);
    $order_no = isset($_GET['o']) ? mysqli_real_escape_string($con, $_GET['o']) : '';
    $customer_id = $_SESSION['auth_user']['user_id'];

    // Verify the order belongs to the user and is in Under Process status
    $query = "SELECT * FROM orders WHERE id = '$order_id' AND customer_id = '$customer_id' AND status = '0'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Update order status to Cancelled (3) and save cancellation reason
        $update_query = "UPDATE orders SET status = '3', cancel_reason = '$cancel_reason' WHERE id = '$order_id'";
        if (mysqli_query($con, $update_query)) {
            redirect("../my_orders.php", "Order cancelled successfully");
        } else {
            redirect("../my_orders.php", "Failed to cancel order: " . mysqli_error($con));
        }
    } else {
        redirect("../my_orders.php", "Order not found or not cancellable");
    }
}
?>