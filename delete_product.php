<?php
session_start();

include 'db.php'; // Include database connection

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete product
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?message=Product deleted successfully");
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    header("Location: dashboard.php"); // Redirect if no product ID is provided
    exit();
}

$conn->close(); // Close the database connection
?>
