<?php
session_start();
include('config/dbcon.php');
include('includes/header.php');
$user_id = $_SESSION['auth_user']['user_id'];

if (!$user_id) {
    echo "You must be logged in to view this page.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>
    
    <div class="login-container">
        <div class="login-form">
            <h2>Change Password</h2>
            <form action="functions/authcode.php" method="post" onsubmit="return validateChangePassword()">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="password">
                        <input type="password" name="current_password" id="current_password" placeholder="Enter current password" required>
                        <img src="assets/images/eye-close.png" id="eyeicon1" onclick="togglePasswordVisibility('current_password', 'eyeicon1')">
                    </div>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <div class="password">
                        <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required
                        onblur="passwordValidation('new_password','passwordErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon2" onclick="togglePasswordVisibility('new_password', 'eyeicon2')">
                    </div>
                    <span id="passwordErr"></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <div class="password">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required
                        onblur="checkPass('confirm_password','new_password','cpassErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon3" onclick="togglePasswordVisibility('confirm_password', 'eyeicon3')">
                    </div>
                    <span id="cpassErr"></span>
                </div>
                <button type="submit" name="change_password_btn" class="button">Change Password</button>
                <p class="register-link">
                    <a href="index.php">Back to Home</a>
                </p>
            </form>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/validation.js"></script>
</body>
</html>
<?php include('includes/footer.php'); ?>