<?php
// includes/db_connect.php

$host = "localhost";
$user = "root";      // Default XAMPP username
$pass = "";          // Default XAMPP password (empty)
$db   = "canteen_db";

// Create Connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check Connection
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>