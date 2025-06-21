   <?php
   ini_set('display_errors', 1);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);

   $to = "bisojchaudhary511@gmail.com"; // Replace with a valid recipient email
   $subject = "Test Email from XAMPP";
   $message = "This is a test email sent from XAMPP.";
   $headers = "From: ashwinchaudhary511@gmail.com\r\n";
   $headers .= "Reply-To: ashwinchaudhary511@gmail.com\r\n";
   $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

   if (mail($to, $subject, $message, $headers)) {
       echo "Email sent successfully!";
   } else {
       echo "Failed to send email.";
       error_log("Mail failed to send to $to at " . date('Y-m-d H:i:s'));
   }
   ?>