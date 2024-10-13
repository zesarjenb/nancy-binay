<?php
class Dashboard {
    private $role;

    public function __construct($role) {
        $this->role = $role;
    }

    public function showStockLevels($stockData) {
        if (in_array($this->role, ['admin', 'staff', 'stock_personnel'])) {
            echo "<h3>Stock Levels</h3>";
            echo "<ul>";
            foreach ($stockData as $item => $quantity) {
                echo "<li>$item: $quantity units</li>";
            }
            echo "</ul>";
        }
    }

    public function showSales($salesToday) {
        if (in_array($this->role, ['admin', 'staff'])) {
            echo "<h3>Today's Sales</h3>";
            echo "<p>₱" . number_format($salesToday) . "</p>";
        }
    }

    public function showExpenses($expensesToday) {
        if (in_array($this->role, ['admin', 'staff'])) {
            echo "<h3>Today's Expenses</h3>";
            echo "<p>₱" . number_format($expensesToday) . "</p>";
        }
    }

    public function showProfit($profitToday) {
        if ($this->role === 'admin') {
            echo "<h3>Today's Profit</h3>";
            echo "<p>₱" . number_format($profitToday) . "</p>";
        }
    }

    public function showStockManagementOption() {
        if ($this->role === 'stock_personnel') {
            echo "<h3>Manage Stock Levels</h3>";
            echo "<p><a href='manage_stocks.php' class='btn btn-primary'>Update Stock Levels</a></p>";
        }
    }
}
?>
