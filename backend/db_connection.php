<?php
// Database connection settings
$host = 'localhost'; // Server address
$dbname = 'outdoorgearhub'; // Database name
$username = 'root'; // Default username in XAMPP
$password = ''; // Default password in XAMPP

try {
    // PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
