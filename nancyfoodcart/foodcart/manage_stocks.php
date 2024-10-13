<?php
// manage_stocks.php
require_once 'database.php';
require_once 'User.php';
require_once 'Dashboard_.php';

// Start the session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Initialize the database connection
$database = new Database();
$conn = $database->getConnection();

// Create the User object and fetch the role
$user = new User($conn);
$role = $_SESSION['role']; // Retrieve the role from the session

// Create a Dashboard object
$dashboard = new Dashboard($role); // Create a Dashboard object with the role

// Dummy data for stock levels (replace with actual data from the database)
$stockData = [
    ['item' => 'Chicken', 'quantity' => 150, 'unit_price' => 120, 'total_value' => 18000],
    ['item' => 'Rice', 'quantity' => 300, 'unit_price' => 50, 'total_value' => 15000],
    ['item' => 'Chicken Skin', 'quantity' => 50, 'unit_price' => 30, 'total_value' => 1500],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Monitor - Food Business System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> <!-- Custom Styles for Better UI -->
    <style>
        body {
            background: linear-gradient(to right, #f5e0b5, #f3d4a0); /* Gradient background */
            font-family: 'Arial', sans-serif;
        }

        .sidebar {
            background-color: #c7a462; /* Light brown color for the sidebar */
            height: 100vh; /* Full height */
            position: fixed; /* Fixed sidebar */
            width: 250px; /* Width of the sidebar */
            padding-top: 20px; 
        }

        .sidebar a {
            color: #4E3B31; /* Dark brown color for sidebar text */
            padding: 15px;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 16px; /* Slightly larger font size */
            font-weight: bold; /* Bold text for sidebar links */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        .sidebar a:hover {
            background-color: #b3752b; /* Darker brown on hover */
            color: white; /* Change text color to white on hover */
        }

        .content {
            margin-left: 250px; /* Make room for the sidebar */
            padding: 20px;
        }

        .card {
            border-radius: 10px; /* Rounded corners */
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth scaling and shadow transition */
        }

        .card:hover {
            transform: translateY(-5px); /* Slightly lift the card on hover */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }

        .card-header {
            background-color: #d4c09a; /* Header color for cards */
            color: #4E3B31; /* Dark brown text color */
            font-weight: bold;
            font-size: 20px; /* Slightly larger font size */
            border-top-left-radius: 10px; /* Rounded corners for header */
            border-top-right-radius: 10px; /* Rounded corners for header */
        }

        .icon-style {
            font-size: 25px;
            margin-right: 10px;
        }

        .table {
            margin-top: 20px;
        }

        .btn-custom {
            background-color: #4E3B31; /* Dark brown */
            color: white; /* White text */
            border-radius: 5px; /* Rounded button corners */
            margin-bottom: 15px; /* Margin below buttons */
        }

        .btn-custom:hover {
            background-color: #b3752b; /* Lighter brown on hover */
            color: white; /* Keep text color white */
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="logo-container"> <!-- Flex container for logo and business name -->
        <img src="chicken.png" alt="Logo" class="logo"> <!-- Logo -->
        <h3 class="business-name">Nancy's Foodcart</h3> <!-- Business name -->
    </div>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_stocks.php">Stocks Monitor</a>
    <a href="sales_track.php" class="active">Sales Track</a>
    <a href="expense_track.php">Expense Track</a>
    <a href="logout.php" class="btn btn-outline-light">Logout</a>
</div>s

    <!-- Main Content -->
    <div class="content">
        <h2 class="text-center mt-4" style="color: #4E3B31; font-weight: bold; font-size: 28px;">Stock Monitor</h2>

        <div class="row mt-4">
            <!-- Add New Stock Card -->
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header"><i class="fas fa-plus-circle icon-style"></i>Add New Stock</div>
                    <div class="card-body">
                    <form action="actions/add_stock.php" method="post">
                    <div class="mb-3">
                                <label for="item" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="item" name="item" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="unit_price" class="form-label">Unit Price</label>
                                <input type="number" class="form-control" id="unit_price" name="unit_price" required>
                            </div>
                            <button type="submit" class="btn btn-custom">Add Stock</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stock Levels Table -->
            <div class="col-md-12 mb-4">
                <div class="card shadow">
                    <div class="card-header"><i class="fas fa-boxes icon-style"></i>Current Stock Levels</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stockData as $stock): ?>
                                    <tr>
                                        <td><?php echo $stock['item']; ?></td>
                                        <td><?php echo $stock['quantity']; ?></td>
                                        <td><?php echo $stock['unit_price']; ?></td>
                                        <td><?php echo $stock['total_value']; ?></td>
                                        <td>
                                            <a href="edit_stock.php?id=<?php echo $stock['item']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete_stock.php?id=<?php echo $stock['item']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
