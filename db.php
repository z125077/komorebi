<?php
$host = '172.21.82.208'; 
$dbname = 'komorebi_forum'; 
$username = 'GROUP12'; 
$password = '633'; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>