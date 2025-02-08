<?php
// Database connection parameters
$servername = "localhost"; // Usually localhost
$username = "root";         // MySQL username
$password = "";             // MySQL password (empty for default)
$dbname = "webtech";        // Name of your database

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
