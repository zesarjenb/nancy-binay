<?php
class Sales {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Method to fetch all sales records
    public function getSalesRecords() {
        $query = "SELECT sale_date AS date, amount FROM sales"; // Adjust the column name to match your DB
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Method to add a sales record
    public function addSalesRecord($date, $amount) {
        $query = "INSERT INTO sales (sale_date, amount) VALUES (:sale_date, :amount)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sale_date', $date);
        $stmt->bindParam(':amount', $amount);
        return $stmt->execute();
    }
    
    
}
?>
