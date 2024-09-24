<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $db = $client->user_auth_system;
    $collection = $db->profiles;
} catch (Exception $e) {
    error_log('MongoDB Connection Error: ' . $e->getMessage());
    die('Failed to connect to MongoDB: ' . $e->getMessage());
}
?>