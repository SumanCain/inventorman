<?php
session_start();
include 'db.php'; // Database connection

// Set default sort column and order
$sort_column = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
$sort_order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc';

// Toggle sorting order
$next_order = $sort_order == 'asc' ? 'desc' : 'asc';

// Search functionality
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search_query ? "WHERE name LIKE '$search_query%'" : '';

// Filter functionality
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$quantity_min = isset($_GET['quantity_min']) ? $_GET['quantity_min'] : '';
$quantity_max = isset($_GET['quantity_max']) ? $_GET['quantity_max'] : '';

// Append filter conditions to search query
if ($price_min !== '' && $price_max !== '') {
    $search_sql .= ($search_sql ? ' AND' : ' WHERE') . " price BETWEEN $price_min AND $price_max";
}
if ($quantity_min !== '' && $quantity_max !== '') {
    $search_sql .= ($search_sql ? ' AND' : ' WHERE') . " quantity BETWEEN $quantity_min AND $quantity_max";
}

// Fetch products from the database with sorting, search, and filter
$sql = "SELECT * FROM products $search_sql ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        th a {
            color: white;
            text-decoration: none;
        }
        .button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 15px;
        }
        .search-input {
            padding: 8px;
            font-size: 16px;
            width: 200px;
            margin-right: 5px;
        }
        .search-button {
            padding: 8px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .filter-button {
            padding: 8px 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 5px;
        }
        .filter-menu {
            display: none;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .filter-input {
            padding: 5px;
            margin: 5px 0;
            width: 100px;
        }
    </style>
    <script>
        function toggleFilterMenu() {
            const filterMenu = document.getElementById('filterMenu');
            filterMenu.style.display = filterMenu.style.display === 'block' ? 'none' : 'block';
        }

        function resetFilters() {
            document.getElementById('price_min').value = '';
            document.getElementById('price_max').value = '';
            document.getElementById('quantity_min').value = '';
            document.getElementById('quantity_max').value = '';
        }
    </script>
</head>
<body>

<h1>Inventory Management Dashboard</h1>

<div class="search-container">
    <form method="GET" action="dashboard.php" style="display: flex;">
        <input type="text" name="search" class="search-input" placeholder="Search Product..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit" class="search-button">Search</button>
        <button type="button" class="filter-button" onclick="toggleFilterMenu()">Filter</button>
    </form>
</div>

<div id="filterMenu" class="filter-menu">
    <form method="GET" action="dashboard.php">
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
        <label for="price_min">Price Min:</label>
        <input type="number" name="price_min" id="price_min" class="filter-input" value="<?php echo htmlspecialchars($price_min); ?>">
        
        <label for="price_max">Price Max:</label>
        <input type="number" name="price_max" id="price_max" class="filter-input" value="<?php echo htmlspecialchars($price_max); ?>">
        
        <label for="quantity_min">Quantity Min:</label>
        <input type="number" name="quantity_min" id="quantity_min" class="filter-input" value="<?php echo htmlspecialchars($quantity_min); ?>">
        
        <label for="quantity_max">Quantity Max:</label>
        <input type="number" name="quantity_max" id="quantity_max" class="filter-input" value="<?php echo htmlspecialchars($quantity_max); ?>">
        
        <button type="submit" class="search-button">Apply Filters</button>
        <button type="button" class="search-button" onclick="resetFilters()">Reset Filters</button>
    </form>
</div>

<a href="add_product.php" class="button">Add Product</a>

<table>
    <tr>
        <th><a href="?sort_by=id&order=<?php echo $next_order; ?>&search=<?php echo urlencode($search_query); ?>">ID</a></th>
        <th><a href="?sort_by=name&order=<?php echo $next_order; ?>&search=<?php echo urlencode($search_query); ?>">Name</a></th>
        <th><a href="?sort_by=category&order=<?php echo $next_order; ?>&search=<?php echo urlencode($search_query); ?>">Category</a></th>
        <th><a href="?sort_by=price&order=<?php echo $next_order; ?>&search=<?php echo urlencode($search_query); ?>">Price</a></th>
        <th><a href="?sort_by=quantity&order=<?php echo $next_order; ?>&search=<?php echo urlencode($search_query); ?>">Quantity</a></th>
        <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6">No products found.</td>
        </tr>
    <?php endif; ?>
</table>

<a href="logout.php" class="button">Logout</a>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
