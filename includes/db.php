<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'photography_club';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS members (
            member_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            full_name VARCHAR(100) NOT NULL,
            join_date DATE NOT NULL,
            profile_pic VARCHAR(255) DEFAULT 'default.jpg',
            bio TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS photos (
            photo_id INT AUTO_INCREMENT PRIMARY KEY,
            member_id INT NOT NULL,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            file_path VARCHAR(255) NOT NULL,
            upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            category VARCHAR(50),
            FOREIGN KEY (member_id) REFERENCES members(member_id)
        );
    ");
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Helper functions
function is_logged_in() {
    return isset($_SESSION['member_id']);
}

function redirect_if_not_logged_in() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}
?>