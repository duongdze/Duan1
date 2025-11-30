<?php
require_once './configs/env.php';

echo "<h2>🔍 Debug Tours Versions Complete Flow</h2>";

// Test 1: Routes
echo "<h3>1️⃣ Test Routes Configuration</h3>";
try {
    require_once './controller/admin/TourVersionController.php';
    $controller = new TourVersionController();
    echo "✅ TourVersionController loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ TourVersionController error: " . $e->getMessage() . "<br>";
}

// Test 2: Model
echo "<h3>2️⃣ Test Model Connection</h3>";
try {
    require_once './models/TourVersion.php';
    $model = new TourVersion();
    $versions = $model->getAllVersions();
    echo "✅ TourVersion model working - Found " . count($versions) . " versions<br>";
} catch (Exception $e) {
    echo "❌ TourVersion model error: " . $e->getMessage() . "<br>";
}

// Test 3: Form Action URL
echo "<h3>3️⃣ Test Form Action URL</h3>";
$createAction = BASE_URL_ADMIN . '&action=tours_versions/create';
$updateAction = BASE_URL_ADMIN . '&action=tours_versions/update&id=1';
echo "Create URL: <strong>{$createAction}</strong><br>";
echo "Update URL: <strong>{$updateAction}</strong><br>";

// Test 4: Session
echo "<h3>4️⃣ Test Session</h3>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session status: " . (session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive') . "<br>";

// Test 5: Database Insert (same as controller)
echo "<h3>5️⃣ Test Database Insert (Controller Logic)</h3>";
try {
    $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME), DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

    // Simulate form data
    $data = [
        'name' => 'Debug Test ' . date('His'),
        'description' => 'Test from debug script',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Test validation
    $errors = [];
    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'Tên phiên bản không được để trống';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO tour_versions (name, description, status, created_at, updated_at) 
                VALUES (:name, :description, :status, :created_at, :updated_at)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            $insertId = $pdo->lastInsertId();
            echo "✅ Direct database insert successful - ID: {$insertId}<br>";
        } else {
            echo "❌ Direct database insert failed<br>";
        }
    } else {
        echo "❌ Validation failed: " . implode(', ', $errors) . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Database test error: " . $e->getMessage() . "<br>";
}

// Test 6: Model Insert
echo "<h3>6️⃣ Test Model Insert</h3>";
try {
    $model = new TourVersion();
    $data = [
        'name' => 'Model Test ' . date('His'),
        'description' => 'Test via model',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $result = $model->insert($data);
    if ($result) {
        echo "✅ Model insert successful<br>";
    } else {
        echo "❌ Model insert failed<br>";
    }
} catch (Exception $e) {
    echo "❌ Model insert error: " . $e->getMessage() . "<br>";
}

// Test 7: Check actual form submission
echo "<h3>7️⃣ Check Form Submission Data</h3>";
if ($_POST) {
    echo "POST data received:<br>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo "No POST data - try submitting the form first<br>";
}

// Test 8: Check current session messages
echo "<h3>8️⃣ Current Session Messages</h3>";
if (isset($_SESSION['success'])) {
    echo "✅ Success: " . $_SESSION['success'] . "<br>";
}
if (isset($_SESSION['error'])) {
    echo "❌ Error: " . $_SESSION['error'] . "<br>";
}
if (isset($_SESSION['form_errors'])) {
    echo "❌ Form Errors: <pre>";
    print_r($_SESSION['form_errors']);
    echo "</pre>";
}

echo "<h3>🔗 Test Links</h3>";
echo "<a href='" . BASE_URL_ADMIN . "&action=tours_versions/create' target='_blank'>Open Create Form</a><br>";
echo "<a href='" . BASE_URL_ADMIN . "&action=tours_versions' target='_blank'>Open List Page</a><br>";

echo "<h3>📝 Next Steps:</h3>";
echo "<ol>";
echo "<li>Try submitting the form with this debug page open</li>";
echo "<li>Check if POST data appears above</li>";
echo "<li>Check for any session messages</li>";
echo "<li>Look for any errors in the steps above</li>";
echo "</ol>";
