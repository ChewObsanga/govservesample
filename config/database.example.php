<?php
// Example database configuration
// Copy this file to database.php and update with your actual credentials

function getDBConnection() {
    $host = 'localhost';
    $port = '3307';
    $dbname = 'caloocan_social_services';
    $username = 'your_username';
    $password = 'your_password';
    
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection failed: ' . $e->getMessage());
        throw new Exception('Database connection failed');
    }
}
?>
