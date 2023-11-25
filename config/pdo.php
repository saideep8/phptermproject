<?php

$serverName = "localhost";
$dbUsername = "deep";
$dbPassword = "Db@12345";
$dbName = "dukhan";
$port = "3306";

try {
    $pdo = new PDO("mysql:host=$serverName; port=$port; dbname=$dbName", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<h2>Something Went wrong!</h2>";
    error_log("Connection error: " . $e->getMessage());
}
