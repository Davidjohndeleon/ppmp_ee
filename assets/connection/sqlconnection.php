<?php
$dbHost = "127.0.0.1";
$dbUser = "root";
$dbPassword = "";
$dbName = "ppmpee";

try {
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enables error reporting
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch as associative array
        PDO::ATTR_EMULATE_PREPARES => false, // Improves security
    ]);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage()); // Stops execution on failure
}

$status = "connected";
?>
