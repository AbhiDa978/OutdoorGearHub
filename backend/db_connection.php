<?php
// Database connection settings
$host = 'localhost';
$dbname = 'outdoorgearhub';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  
        PDO::ATTR_EMULATE_PREPARES => false, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 
    ]);
} catch (PDOException $e) {
    echo "Connection failed: " . htmlspecialchars($e->getMessage());
    exit();
}
?>
