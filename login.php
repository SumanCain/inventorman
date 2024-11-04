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

// Query the database for the admin
$sql = "SELECT password FROM admin WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    
    // Verify the password
    if (password_verify($password, $row['password'])) {
        //echo "Login successful! Welcome, $username.";
        // Redirect to dashboard or main page
         header("Location: dashboard.php");
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No admin found with this username.";
}

// Close the connection
mysqli_close($conn);
?>
