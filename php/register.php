<?php
require 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// Password validation
if (strlen($password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters']);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Check if email already exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
    exit;
}

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
if ($stmt->execute([$email, $hashed_password])) {
    echo json_encode(['status' => 'success', 'message' => 'Registration successful']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed']);

    
}


header('Content-Type: application/json');

// On successful registration
echo json_encode(['status' => 'success', 'message' => 'Registration successful']);

// On failure
echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
?>