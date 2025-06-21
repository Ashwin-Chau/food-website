<?php
session_start();

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
    <title>Verify OTP</title>
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
            <h2>Verify OTP</h2>
            <p>Please enter the OTP sent to your email.</p>
            <form action="functions/authcode.php" method="post">
                <div class="form-group">
                    <label for="otp">OTP</label>
                    <input type="text" name="otp" id="otp" placeholder="Enter OTP" required>
                </div>
                <button type="submit" name="verify_otp_btn" class="button">Verify OTP</button>
                <p class="resend-link">
                    Didn't receive OTP? 
                    <a href="functions/authcode.php?resend_otp=1" id="resend-otp" onclick="disableResend()">Resend OTP</a>
                    <span id="resend-timer" style="display: none;"> (Wait <span id="timer">30</span> seconds)</span>
                </p>
                <p class="register-link">
                    <a href="register.php">Back to Register</a>
                </p>
            </form>
        </div>
    </div>
    <script>
        function disableResend() {
            const resendLink = document.getElementById('resend-otp');
            const resendTimer = document.getElementById('resend-timer');
            const timerDisplay = document.getElementById('timer');
            let timeLeft = 30;

            resendLink.style.pointerEvents = 'none';
            resendLink.style.color = '#999';
            resendTimer.style.display = 'inline';

            const countdown = setInterval(() => {
                timeLeft--;
                timerDisplay.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.color = '#007bff';
                    resendTimer.style.display = 'none';
                }
            }, 1000);
        }
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>