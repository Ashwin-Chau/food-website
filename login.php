
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
    <title>Login Form</title>
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
            <h2>Login Form</h2>
            <form action="functions/authcode.php" method="post">

                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" class="email" placeholder="Enter your email" id="email">
                </div>

                <div class="form-group">
                    <label for="">Password</label>
                    <div class="password">
                        <input type="password" class="password" name="password" id="password1" placeholder="Enter your password" required>
                        <img src="assets/images/eye-close.png" id="eyeicon1" onclick="togglePasswordVisibility('password1', 'eyeicon1')">
                    </div>      
                </div>
              
                <button type="submit" name="login_btn" class="button">Login</button>

            </form>

            <p class="register-link">
                <a href="forgot_password.php">Forgot Password</a>
            </p>

            <p class="register-link">
                <a href="register.php">If don't have an account? Signup</a>
            </p>

        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
