<?php
session_start();

include 'db.php'; // Include database connection

// Initialize variables
$name = $category = $price = $quantity = "";
$successMsg = $errorMsg = "";

// Get the product ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM products WHERE id = $id");

    // Check if product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $name = $product['name'];
        $category = $product['category'];
        $price = $product['price'];
        $quantity = $product['quantity'];
    } else {
        $errorMsg = "Product not found.";
    }
} else {
    header("Location: dashboard.php"); // Redirect if no product ID is provided
    exit();
}

// Update product details on form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Validate and update product
    if (!empty($name) && !empty($category) && is_numeric($price) && is_numeric($quantity)) {
        $sql = "UPDATE products SET name = '$name', category = '$category', price = $price, quantity = $quantity WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            $successMsg = "Product updated successfully!";
        } else {
            $errorMsg = "Error updating product: " . $conn->error;
        }
    } else {
        $errorMsg = "Please fill in all fields correctly.";
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>
    
    <?php if ($successMsg) echo "<p class='message'>$successMsg</p>"; ?>
    <?php if ($errorMsg) echo "<p class='error'>$errorMsg</p>"; ?>

    <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>" required>
        <input type="number" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
        <input type="submit" value="Update Product">
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
