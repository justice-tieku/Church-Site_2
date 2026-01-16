<?php
// Database setup script
$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    // Connect without database first
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS church_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database 'church_website' created successfully<br>";
    
    // Use the database
    $pdo->exec("USE church_website");
    
    // Create admins table
    $pdo->exec("CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        token VARCHAR(64) NULL,
        token_expires DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "✓ Table 'admins' created successfully<br>";
    
    // Create index
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_admins_email ON admins(email)");
    echo "✓ Index created successfully<br>";
    
    // Create messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        speaker VARCHAR(255) NOT NULL,
        date DATE NOT NULL,
        description TEXT,
        filename VARCHAR(255) NOT NULL,
        media_type ENUM('audio', 'video') DEFAULT 'audio',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "✓ Table 'messages' created successfully<br>";
    
    echo "<br><strong>Database setup completed successfully!</strong><br>";
    echo "You can now register an account and use the system.<br>";
    echo "<a href='login.html'>Go to Login/Register</a>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
