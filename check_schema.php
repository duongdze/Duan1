<?php
require_once 'config/config.php';
require_once 'models/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Get columns from bookings table
    $stmt = $conn->query("SHOW COLUMNS FROM bookings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in 'bookings' table:\n";
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
