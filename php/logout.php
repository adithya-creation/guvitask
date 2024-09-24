<?php
require 'redis.php';

$sessionID = $_POST['sessionID'];
$redis->del('session:' . $sessionID);

echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
?>