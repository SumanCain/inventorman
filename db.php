<?php
$servername = "localhost";  // Server name, typically "localhost"
$username = "root"; // MySQL username
$password = ""; // MySQL password
$dbname = "inventory_management"; // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
