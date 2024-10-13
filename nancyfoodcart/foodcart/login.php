<?php
require 'User.php';
require 'database.php';

session_start(); // Start the session

// Initialize the database connection
$database = new Database();
$conn = $database->getConnection();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Get the role selection from the form

    $user = new User($conn);

    // Attempt to login with fixed credentials for the selected role
    if ($user->loginWithFixedCredentials($username, $password, $role)) {
        header("Location: dashboard.php"); // Redirect to dashboard on successful login
        exit;
    } else {
        $error = "Invalid username, password, or role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In - Food Business System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex align-items-center justify-content-center min-vh-100 bg-login">
    <div class="form-container text-center shadow-lg p-4 rounded">
        <h2 class="mb-4 text-primary">Welcome Back!</h2>
        <img src="chicken.png" alt="Food Business Logo" class="mb-3" width="100">

        <!-- Display error message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <!-- Role Selection Dropdown -->
            <div class="form-floating mb-3">
                <select class="form-select" id="role" name="role" required>
                    <option selected disabled value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="stock_personnel">Stock Personnel</option>
                </select>
                <label for="role">Role</label>
            </div>

            <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
        </form>
    </div>
</div>
</body>
</html>
