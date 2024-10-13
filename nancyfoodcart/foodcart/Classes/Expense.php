<?php
class Expense {
    private $conn;
    private $table_name = "expenses"; // Change as per your database table

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getExpenseRecords() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        // Fetching the data as associative array
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Creating an array of Expense objects
        $expenseObjects = [];
        foreach ($expenses as $expenseData) {
            $expense = new Expense($this->conn); // Create a new Expense instance
            // Set the properties for the object
            $expense->date = $expenseData['date'];
            $expense->amount = $expenseData['amount'];
            $expense->description = $expenseData['description'];
            $expenseObjects[] = $expense; // Add the object to the array
        }
        
        return $expenseObjects; // Return array of Expense objects
    }

    public function addExpenseRecord($date, $amount, $description) {
        $query = "INSERT INTO " . $this->table_name . " (date, amount, description) VALUES (:date, :amount, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }
    
    // Add the getter methods
    public function getDate() {
        return $this->date; 
    }

    public function getAmount() {
        return $this->amount; 
    }

    public function getDescription() {
        return $this->description; 
    }
}
