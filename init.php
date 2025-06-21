<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
$host = 'localhost';
$dbname = 'food'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

$con = mysqli_connect($host, $username, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Include utility functions
require_once __DIR__ . '/functions/myfunctions.php';
?>