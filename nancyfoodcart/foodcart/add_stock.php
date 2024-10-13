<?php
// add_stock.php

require_once 'database.php'; // Include your database connection

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

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    // Calculate the total value
    $total_value = $quantity * $unit_price;

    // Prepare the SQL query to insert the stock data into the database
    $query = "INSERT INTO stocks (item, quantity, unit_price, total_value) VALUES (:item, :quantity, :unit_price, :total_value)";
    $stmt = $conn->prepare($query);

    // Bind the parameters to the query
    $stmt->bindParam(':item', $item);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':unit_price', $unit_price);
    $stmt->bindParam(':total_value', $total_value);

    // Execute the query and check if the insertion was successful
    if ($stmt->execute()) {
        // If successful, redirect back to manage_stocks.php with a success message
        $_SESSION['message'] = "Stock added successfully!";
        header("Location: manage_stocks.php");
        exit();
    } else {
        // If insertion failed, show an error
        $_SESSION['error'] = "Failed to add stock. Please try again.";
        header("Location: manage_stocks.php");
        exit();
    }
} else {
    // If the request method is not POST, redirect to manage_stocks.php
    header("Location: manage_stocks.php");
    exit();
}
?>
