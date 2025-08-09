<?php

$host = 'localhost';
$dbname = 'photography_club';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
    $pdo->exec("
         CREATE TABLE IF NOT EXISTS events (
        event_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        event_date DATE NOT NULL,
        location VARCHAR(100) NOT NULL,
        organizer_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (organizer_id) REFERENCES members(member_id)
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
        
        CREATE TABLE IF NOT EXISTS events (
            event_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(100) NOT NULL,
            description TEXT,
            event_date DATE NOT NULL,
            location VARCHAR(255) NOT NULL,
            organizer_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (organizer_id) REFERENCES members(member_id)
        );
        
        CREATE TABLE IF NOT EXISTS event_participants (
        event_id INT NOT NULL,
        member_id INT NOT NULL,
        PRIMARY KEY (event_id, member_id),
        FOREIGN KEY (event_id) REFERENCES events(event_id),
        FOREIGN KEY (member_id) REFERENCES members(member_id)
    );
      CREATE TABLE IF NOT EXISTS event_photos (
        event_id INT NOT NULL,
        photo_id INT NOT NULL,
        PRIMARY KEY (event_id, photo_id),
        FOREIGN KEY (event_id) REFERENCES events(event_id),
        FOREIGN KEY (photo_id) REFERENCES photos(photo_id)
    );


    ");
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Helper functions
function is_logged_in() {
    return isset($_SESSION['member_id']);
}

function redirect_if_not_logged_in()
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}