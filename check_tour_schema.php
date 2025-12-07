<?php
require_once 'config/config.php';
require_once 'models/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Get columns from tours table
    $stmt = $conn->query("SHOW COLUMNS FROM tours");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Columns in 'tours' table:\n";
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (Type: " . $col['Type'] . ")\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
