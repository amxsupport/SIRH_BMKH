<?php
require_once 'config/database.php';

function createUsersTable($db) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        first_name VARCHAR(50),
        last_name VARCHAR(50),
        is_active BOOLEAN DEFAULT true,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        password_reset_token VARCHAR(100) NULL,
        password_reset_expires TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $db->exec($sql);
        echo "Users table created successfully or already exists\n";
        return true;
    } catch (PDOException $e) {
        echo "Error creating users table: " . $e->getMessage() . "\n";
        return false;
    }
}

try {
    // Create database connection
    $db = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8",
        $db_config['username'],
        $db_config['password']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table if it doesn't exist
    if (!createUsersTable($db)) {
        die("Failed to create users table\n");
    }
    
    // Check if admin user exists
    $query = "SELECT user_id FROM users WHERE username = 'admin'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $adminExists = $stmt->fetch(PDO::FETCH_ASSOC);

    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    if ($adminExists) {
        // Update existing admin user
        $query = "UPDATE users SET password_hash = :hash WHERE username = 'admin'";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([':hash' => $hash]);
        
        if ($result) {
            echo "Admin password reset successfully\n";
            echo "Username: admin\n";
            echo "Password: admin123\n";
        } else {
            echo "Failed to reset admin password\n";
        }
    } else {
        // Create new admin user
        $query = "INSERT INTO users (username, email, password_hash, role, first_name, last_name, is_active) 
                 VALUES (:username, :email, :password_hash, :role, :first_name, :last_name, :is_active)";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([
            ':username' => 'admin',
            ':email' => 'admin@example.com',
            ':password_hash' => $hash,
            ':role' => 'admin',
            ':first_name' => 'System',
            ':last_name' => 'Administrator',
            ':is_active' => true
        ]);
        
        if ($result) {
            echo "Admin user created successfully\n";
            echo "Username: admin\n";
            echo "Password: admin123\n";
            echo "Email: admin@example.com\n";
        } else {
            echo "Failed to create admin user\n";
        }
    }
    
    // Verify the password hash
    $query = "SELECT password_hash FROM users WHERE username = 'admin'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $storedHash = $stmt->fetchColumn();
    
    echo "\nVerifying password hash:\n";
    echo "Stored hash: " . $storedHash . "\n";
    echo "Verification test: " . (password_verify($password, $storedHash) ? "PASSED" : "FAILED") . "\n";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
