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
    <title>Reset Password</title>
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
            <h2>Reset Password</h2>
            <form action="functions/authcode.php" method="post" onsubmit="return validateResetPassword()">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="password">
                        <input type="password" name="password" id="password" placeholder="Enter new password" required
                        onblur="passwordValidation('password','passwordErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon" onclick="togglePasswordVisibility('password', 'eyeicon')">
                    </div>
                    <span id="passwordErr"></span>
                </div>
                <div class="form-group">
                    <label for="cpassword">Confirm Password</label>
                    <div class="password">
                        <input type="password" name="cpassword" id="cpassword" placeholder="Confirm new password" required
                        onblur="checkPass('cpassword','password','cpassErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon2" onclick="togglePasswordVisibility('cpassword', 'eyeicon2')">
                    </div>
                    <span id="cpassErr"></span>
                </div>
                <button type="submit" name="reset_password_btn" class="button">Reset Password</button>
                <p class="register-link">
                    <a href="login.php">Back to Login</a>
                </p>
            </form>
        </div>
    </div>
    <script src="assets/js/validation.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>