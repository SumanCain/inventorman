<?php
session_start();

include 'db.php'; // Include database connection

// Initialize variables
$name = $category = $price = $quantity = "";
$successMsg = $errorMsg = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Validate and insert into database
    if (!empty($name) && !empty($category) && is_numeric($price) && is_numeric($quantity)) {
        $sql = "INSERT INTO products (name, category, price, quantity, created_at) VALUES ('$name', '$category', $price, $quantity, NOW())";
        
        if ($conn->query($sql) === TRUE) {
            $successMsg = "Product added successfully!";
            $name = $category = $price = $quantity = ""; // Clear input fields
        } else {
            $errorMsg = "Error: " . $conn->error;
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
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            color: #4CAF50;
            margin: 10px 0;
        }
        .error {
            color: #ff4d4d;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>
    
    <?php if ($successMsg) echo "<p class='message'>$successMsg</p>"; ?>
    <?php if ($errorMsg) echo "<p class='error'>$errorMsg</p>"; ?>

    <form action="add_product.php" method="POST">
        <input type="text" name="name" placeholder="Product Name" value="<?php echo htmlspecialchars($name); ?>" required>
        <input type="text" name="category" placeholder="Category" value="<?php echo htmlspecialchars($category); ?>" required>
        <input type="number" name="price" placeholder="Price" step="0.01" value="<?php echo htmlspecialchars($price); ?>" required>
        <input type="number" name="quantity" placeholder="Quantity" value="<?php echo htmlspecialchars($quantity); ?>" required>
        <input type="submit" value="Add Product">
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
