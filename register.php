<?php
// Include database connection
include('db.php');

// Get values from the form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Check for missing fields
if (empty($username) || empty($password)) {
    die("Please fill all required fields.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into the admin table
$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "Registration successful!";
    // Redirect to login page
    // header("Location: login.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>
