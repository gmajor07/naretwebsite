<?php
$host = "localhost";       // usually localhost
$user = "root";            // your MySQL username
$password = "";            // your MySQL password
$database = "naretweb";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database Connection Failed: " . $e->getMessage();
    exit;
}
?>
