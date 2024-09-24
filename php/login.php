<?php
require 'db.php';
require 'redis.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $sessionID = bin2hex(random_bytes(32));
    $redis->setex('session:' . $sessionID, 3600, $user['id']);
    echo json_encode(['status' => 'success', 'sessionID' => $sessionID]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
}
?>