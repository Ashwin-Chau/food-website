<?php
  
    $host = "localhost";
    $username = "root";
    $password = "";
    $databse = "food";

    // Creating database connection
    $con = mysqli_connect($host, $username, $password, $databse);
 
    // Check database connection
    if(!$con)
    {
        die("Connection Failed: ". mysqli_connect_error());
    }
    
?>