<?php
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local XAMPP Configuration
    $host = 'localhost';
    $dbname = 'rey_portfolio';
    $username = 'root';
    $password = '';
} else {
    // InfinityFree Production Configuration
    $host = 'sql311.infinityfree.com';
    $dbname = 'if0_41997551_rey_portfolio';
    $username = 'if0_41997551';
    $password = 'aZg2BIZX0n0Jp';
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database connection failed. Please ensure the database 'rey_portfolio' exists. Error: " . $e->getMessage());
}
?>
