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
    <title>Register Form</title>
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
            <h2>Register Form</h2>
            <form action="functions/authcode.php" method="post" onsubmit="return validateUserRegister()">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="name" name="name" id="name" placeholder="Enter your name" required onblur="nameValidation('name','nameErr')">
                    <span id="nameErr"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="email" name="email" id="email" placeholder="Enter your email" required
                    onblur="emailValidation('email','emailErr')">
                    <span id="emailErr"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password">
                        <input type="password" class="password" name="password" id="password" placeholder="Enter your password" required
                        onblur="passwordValidation('password','passwordErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon" onclick="togglePasswordVisibility('password', 'eyeicon')">
                    </div>
                    <span id="passwordErr" ></span>
                </div>

                <div class="form-group">
                    <label for="cpassword">Confirm Password</label>
                    <div class="password">
                        <input type="password" class="cpassword" name="cpassword" id="cpassword" placeholder="Confirm your password" required
                        onblur="checkPass('cpassword','password','cpassErr')">
                        <img src="assets/images/eye-close.png" id="eyeicon2" onclick="togglePasswordVisibility('cpassword', 'eyeicon2')" >
                    </div>
                    <span id="cpassErr"></span>
                </div>

                <button type="submit" name="register_btn" class="button">Register</button>

                <!-- <p class="forget-password">
                    <a href="#">Forget password</a>
                </p> -->

                <p class="register-link">
                    <a href="login.php">Already have an account? Login</a>
                </p>

            </form>
            
        </div>
    </div>
    
    <script src="assets/js/script.js"></script>
    <script src="assets/js/validation.js"></script>
    
</body>
</html>
