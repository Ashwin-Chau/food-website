<?php
session_start();
require_once '../config/dbcon.php'; // Adjust to your database connection file
require_once 'authcode.php'; // Adjust to where your send_otp_email function is defined

if (!isset($_SESSION['verify_email'])) {
    redirect("../verify_otp.php", "No email found for OTP verification.");
    exit();
}

$email = $_SESSION['verify_email'];
$otp_type = $_SESSION['otp_type'] ?? 'register';

// Generate new OTP
$new_otp = rand(100000, 999999);

// Update OTP in database
$update_query = $con->prepare("UPDATE customer SET otp = ? WHERE email = ?");
$update_query->bind_param("ss", $new_otp, $email);

if ($update_query->execute()) {
    // Send new OTP to email
    if (send_otp_email($email, $new_otp)) {
        redirect("../verify_otp.php", "New OTP sent to your email.");
    } else {
        redirect("../verify_otp.php", "Failed to send new OTP. Please try again.");
    }
} else {
    redirect("../verify_otp.php", "Something went wrong while generating new OTP.");
}

$update_query->close();
$con->close();
?>