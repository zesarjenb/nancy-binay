<?php
class User {
    private $conn;
    private $username;
    private $password;
    private $role;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function loginWithFixedCredentials($username, $password, $role) {
        // Define fixed credentials based on role
        $fixedCredentials = [
            'admin' => ['username' => 'admin', 'password' => 'admin123'],
            'staff' => ['username' => 'staff', 'password' => 'staff123'],
            'stock_personnel' => ['username' => 'stock', 'password' => 'stock123'],
        ];

        // Validate credentials
        if (isset($fixedCredentials[$role])) {
            $fixedUsername = $fixedCredentials[$role]['username'];
            $fixedPassword = $fixedCredentials[$role]['password'];

            if ($username === $fixedUsername && $password === $fixedPassword) {
                // Store user data in session
                $_SESSION['user_id'] = $username; // Set user ID
                $_SESSION['role'] = $role; // Set user role
                return true;
            }
        }
        return false;
    }

    public function getRole() {
        return $this->role;
    }

    public function getUsername() {
        return $this->username;
    }
}
?>
