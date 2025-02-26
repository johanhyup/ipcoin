<?php
// Database connection details
$servername = "localhost";
$username = "deepsee"; // Replace with your database username
$password = "deepsee!"; // Replace with your database password
$dbname = "base"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Optional: Set character set to UTF-8 for proper encoding
$conn->set_charset("utf8");

// Test the connection
//echo "Database connected successfully!";

// Close the connection

?>


