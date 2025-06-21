<?php 
session_start();

if(isset($_SESSION['auth']))
{
    $_SESSION['message'] = "You are already logged In";
    header('Location: index.php');
    exit();
}

if (isset($_SESSION['message'])) {
    echo "<div class='custom-alert' id='flash-message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>
    <div class="logo-container">
        <a href="index.php">
            <img src="assets/images/Food-Plate.png">
        </a>
    </div>
    
    <div class="login-container">
        <div class="login-form">
            <h2>Forgot Password</h2>
            <p>Enter your email to receive a password reset OTP.</p>
            <form action="functions/authcode.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" name="forgot_password_btn" class="button">Send OTP</button>
                <p class="register-link">
                    <a href="login.php">Back to Login</a>
                </p>
            </form>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>