<?php
require_once './configs/env.php';

try {
    $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME), DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    echo "✅ Kết nối database thành công!<br>";

    // Check if tour_versions table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'tour_versions'");
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "✅ Table 'tour_versions' tồn tại!<br>";

        // Show table structure
        $stmt = $pdo->prepare("DESCRIBE tour_versions");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h3>Table Structure:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Show existing data
        $stmt = $pdo->prepare("SELECT * FROM tour_versions");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h3>Existing Data ({$stmt->rowCount()} rows):</h3>";
        if ($stmt->rowCount() > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Description</th><th>Created</th></tr>";
            foreach ($data as $row) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "<td>" . htmlspecialchars($row['description'] ?? '') . "</td>";
                echo "<td>{$row['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Chưa có dữ liệu nào trong table.</p>";
        }
    } else {
        echo "❌ Table 'tour_versions' KHÔNG tồn tại!<br>";
        echo "<h3>Câu lệnh tạo table:</h3>";
        echo "<pre>";
        echo "CREATE TABLE tour_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);";
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "❌ Lỗi database: " . $e->getMessage();
}
