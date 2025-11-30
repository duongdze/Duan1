<?php
require_once './configs/env.php';

// Simulate form submission
echo "<h2>🧪 Test Form Submission</h2>";

// Test data
$testData = [
    'name' => 'Test Version ' . date('H:i:s'),
    'description' => 'This is a test version created at ' . date('Y-m-d H:i:s'),
    'status' => 'active'
];

echo "<h3>📝 Test Data:</h3>";
echo "<pre>";
print_r($testData);
echo "</pre>";

try {
    // Test database insert
    $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME), DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    $sql = "INSERT INTO tour_versions (name, description, status, created_at, updated_at) 
            VALUES (:name, :description, :status, NOW(), NOW())";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        'name' => $testData['name'],
        'description' => $testData['description'],
        'status' => $testData['status']
    ]);

    if ($result) {
        $insertId = $pdo->lastInsertId();
        echo "✅ <strong>Insert thành công!</strong><br>";
        echo "🆔 ID: {$insertId}<br>";

        // Verify the inserted data
        $stmt = $pdo->prepare("SELECT * FROM tour_versions WHERE id = ?");
        $stmt->execute([$insertId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<h3>✅ Dữ liệu đã thêm:</h3>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Status</th><th>Description</th></tr>";
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "❌ <strong>Insert thất bại!</strong><br>";
        echo "Error info: " . print_r($stmt->errorInfo(), true);
    }
} catch (PDOException $e) {
    echo "❌ <strong>Lỗi database:</strong> " . $e->getMessage();
}

// Test duplicate validation
echo "<h3>🔍 Test Duplicate Validation:</h3>";

try {
    $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME), DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    // Test with existing name "Tiêu chuẩn"
    $existingName = "Tiêu chuẩn";
    $sql = "SELECT id, name FROM tour_versions WHERE name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$existingName]);
    $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Testing with name: <strong>'{$existingName}'</strong><br>";
    if (!empty($existing)) {
        echo "❌ <strong>Duplicate found!</strong><br>";
        echo "Existing IDs: ";
        foreach ($existing as $row) {
            echo "#{$row['id']} ('{$row['name']}') ";
        }
        echo "<br>";
        echo "=> Validation sẽ chặn tên này<br>";
    } else {
        echo "✅ <strong>No duplicate found</strong><br>";
    }

    // Test with new unique name
    $uniqueName = "Test Unique " . uniqid();
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uniqueName]);
    $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<br>Testing with name: <strong>'{$uniqueName}'</strong><br>";
    if (!empty($existing)) {
        echo "❌ <strong>Duplicate found!</strong><br>";
    } else {
        echo "✅ <strong>No duplicate found - có thể thêm!</strong><br>";
    }
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}

echo "<h3>🔧 Recommendations:</h3>";
echo "<ul>";
echo "<li>Nếu muốn cho phép trùng tên: Xóa validation duplicate check</li>";
echo "<li>Nếu muốn giữ validation: Sửa logic để chỉ check trong cùng tour</li>";
echo "<li>Test với tên duy nhất như: 'Test " . date('His') . "'</li>";
echo "</ul>";
