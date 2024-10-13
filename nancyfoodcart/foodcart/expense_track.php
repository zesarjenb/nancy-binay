<?php
require_once 'database.php';
require_once 'SessionManager.php';
require_once 'classes/Expense.php'; // Adjust the path to point to the correct location

// Initialize session and check if the user is logged in
$sessionManager = new SessionManager();
if (!$sessionManager->isLoggedIn()) {
    $sessionManager->redirect('login.php');
}

// Initialize the database connection
$database = new Database();
$conn = $database->getConnection();

// Create Expense object
$expense = new Expense($conn);

// Fetch expense data
$expensesData = $expense->getExpenseRecords(); // A method to fetch all expenses

// Check if the form is submitted for adding an expense record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    // Add the expense record
    $expense->addExpenseRecord($date, $amount, $description);

    // Refresh the expenses data after adding a new record
    $expensesData = $expense->getExpenseRecords();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Track - Food Business System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            background: linear-gradient(to right, #f5e0b5, #f3d4a0);
        }

        .sidebar {
            background-color: #c7a462;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }

        .sidebar a {
            color: #4E3B31;
            padding: 15px;
            text-decoration: none;
            display: block;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #b3752b;
            color: white;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        .form-container {
            margin-bottom: 30px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #d4c09a;
            font-size: 20px;
            font-weight: bold;
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
</div>

    <!-- Main Content -->
    <div class="content">
        <h2 class="text-center">Expense Tracking</h2>

        <!-- Form to Add New Expense Record -->
        <div class="form-container card shadow">
            <div class="card-header">Add New Expense Record</div>
            <div class="card-body">
                <form action="expense_track.php" method="post">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="date" name="date" required>
                        <label for="date">Date</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Expense Amount" required>
                        <label for="amount">Expense Amount</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" required>
                        <label for="description">Description</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Expense</button>
                </form>
            </div>
        </div>

        <!-- Expense Records Table -->
        <div class="table-container card shadow">
            <div class="card-header">Daily Expense Records</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expense Amount (PHP)</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($expensesData as $expense): ?>
                        <tr>
                            <td><?= $expense->getDate(); ?></td>
                            <td><?= $expense->getAmount(); ?></td>
                            <td><?= $expense->getDescription(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="total-expenses">
                    <h5>Total Expenses: 
                        <?= array_sum(array_column($expensesData, 'amount')); ?> PHP
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
