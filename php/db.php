<?php
$host = 'localhost';
$db   = 'user_auth_system';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";




$options = [];
$pdo = new PDO($dsn, $user, $pass, $options);
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>