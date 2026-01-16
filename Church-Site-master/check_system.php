<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Check - Cross Passion Church</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .check-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #ddd;
        }
        .check-item.success {
            border-left-color: #4CAF50;
        }
        .check-item.error {
            border-left-color: #f44336;
        }
        .check-item.warning {
            border-left-color: #ff9800;
        }
        h1 {
            color: #333;
        }
        .status {
            font-weight: bold;
            margin-right: 10px;
        }
        .success .status { color: #4CAF50; }
        .error .status { color: #f44336; }
        .warning .status { color: #ff9800; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 5px;
            background: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background: #0b7dda;
        }
    </style>
</head>
<body>
    <h1>🔧 Church Website System Check</h1>
    
    <?php
    $checks = [];
    
    // Check 1: PHP Version
    $phpVersion = phpversion();
    $checks[] = [
        'status' => version_compare($phpVersion, '7.0', '>=') ? 'success' : 'error',
        'title' => 'PHP Version',
        'message' => "PHP version: $phpVersion " . (version_compare($phpVersion, '7.0', '>=') ? '✓' : '✗ (Need 7.0+)')
    ];
    
    // Check 2: PDO Extension
    $checks[] = [
        'status' => extension_loaded('pdo') ? 'success' : 'error',
        'title' => 'PDO Extension',
        'message' => extension_loaded('pdo') ? 'PDO is installed ✓' : 'PDO is not installed ✗'
    ];
    
    // Check 3: PDO MySQL Extension
    $checks[] = [
        'status' => extension_loaded('pdo_mysql') ? 'success' : 'error',
        'title' => 'PDO MySQL Extension',
        'message' => extension_loaded('pdo_mysql') ? 'PDO MySQL is installed ✓' : 'PDO MySQL is not installed ✗'
    ];
    
    // Check 4: Database Connection
    try {
        $pdo = new PDO("mysql:host=localhost", "root", "");
        $checks[] = [
            'status' => 'success',
            'title' => 'MySQL Connection',
            'message' => 'Successfully connected to MySQL ✓'
        ];
        
        // Check 5: Database Exists
        $stmt = $pdo->query("SHOW DATABASES LIKE 'church_website'");
        $dbExists = $stmt->rowCount() > 0;
        
        if ($dbExists) {
            $checks[] = [
                'status' => 'success',
                'title' => 'Database',
                'message' => 'Database "church_website" exists ✓'
            ];
            
            // Check 6: Tables Exist
            $pdo->exec("USE church_website");
            $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
            $tableExists = $stmt->rowCount() > 0;
            
            if ($tableExists) {
                $checks[] = [
                    'status' => 'success',
                    'title' => 'Admins Table',
                    'message' => 'Table "admins" exists ✓'
                ];
                
                // Check admin count
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
                $count = $stmt->fetch()['count'];
                $checks[] = [
                    'status' => $count > 0 ? 'success' : 'warning',
                    'title' => 'Admin Accounts',
                    'message' => "Found $count admin account(s) " . ($count > 0 ? '✓' : '⚠ No accounts yet')
                ];
            } else {
                $checks[] = [
                    'status' => 'error',
                    'title' => 'Admins Table',
                    'message' => 'Table "admins" does not exist ✗'
                ];
            }
        } else {
            $checks[] = [
                'status' => 'error',
                'title' => 'Database',
                'message' => 'Database "church_website" does not exist ✗'
            ];
        }
        
    } catch (PDOException $e) {
        $checks[] = [
            'status' => 'error',
            'title' => 'MySQL Connection',
            'message' => 'Failed to connect to MySQL: ' . $e->getMessage() . ' ✗'
        ];
    }
    
    // Check 7: API Files
    $apiFiles = ['api/auth.php', 'api/includes/db.php'];
    foreach ($apiFiles as $file) {
        $checks[] = [
            'status' => file_exists($file) ? 'success' : 'error',
            'title' => 'File: ' . $file,
            'message' => file_exists($file) ? "File exists ✓" : "File missing ✗"
        ];
    }
    
    // Display results
    foreach ($checks as $check) {
        echo "<div class='check-item {$check['status']}'>";
        echo "<span class='status'>[" . strtoupper($check['status']) . "]</span>";
        echo "<strong>{$check['title']}:</strong> {$check['message']}";
        echo "</div>";
    }
    
    // Recommendations
    $hasErrors = false;
    foreach ($checks as $check) {
        if ($check['status'] === 'error') {
            $hasErrors = true;
            break;
        }
    }
    
    echo "<div style='margin-top: 30px; padding: 20px; background: white; border-radius: 5px;'>";
    echo "<h2>📋 Next Steps:</h2>";
    
    if ($hasErrors) {
        echo "<p><strong>⚠️ Issues detected! Please:</strong></p>";
        echo "<ol>";
        echo "<li>Make sure XAMPP/WAMP is running</li>";
        echo "<li>Start Apache and MySQL services</li>";
        echo "<li>Run the database setup script</li>";
        echo "</ol>";
        echo "<a href='setup_database.php' class='btn'>🔧 Run Database Setup</a>";
    } else {
        echo "<p><strong>✅ Everything looks good!</strong></p>";
        echo "<p>Your system is ready. You can now:</p>";
        echo "<a href='index.html' class='btn'>🏠 Go to Home Page</a>";
        echo "<a href='login.html' class='btn'>🔐 Login/Register</a>";
    }
    
    echo "</div>";
    ?>
    
</body>
</html>
