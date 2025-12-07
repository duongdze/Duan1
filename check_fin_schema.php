<?php
require_once 'config/config.php';
require_once 'models/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $tables = ['booking_suppliers_assignment', 'suppliers', 'bookings'];
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW COLUMNS FROM $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Table: $table\n";
        foreach ($columns as $col) {
            echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
