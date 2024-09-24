<?php
require_once __DIR__ . '/mongo.php';
require_once __DIR__ . '/redis.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sessionID = $_REQUEST['sessionID'] ?? '';
$userID = $redis->get('session:' . $sessionID);

if (!$userID) {
    echo json_encode(['status' => 'error', 'message' => 'Session expired']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $profile = $collection->findOne(['user_id' => $userID]);
        if ($profile) {
            echo json_encode([
                'status' => 'success',
                'profile' => [
                    'age' => $profile['age'] ?? '',
                    'dob' => $profile['dob'] ?? '',
                    'contact' => $profile['contact'] ?? ''
                ]
            ]);
        } else {
            echo json_encode(['status' => 'success', 'profile' => ['age' => '', 'dob' => '', 'contact' => '']]);
        }
    } catch (Exception $e) {
        error_log('MongoDB GET Error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve profile: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = filter_var($_POST['age'] ?? '', FILTER_VALIDATE_INT);
    $dob = $_POST['dob'] ?? '';
    $contact = $_POST['contact'] ?? '';

    if (!$age || $age <= 0 || $age > 120) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid age']);
        exit;
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid date format']);
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid contact number']);
        exit;
    }

    try {
        $result = $collection->updateOne(
            ['user_id' => $userID],
            ['$set' => ['age' => $age, 'dob' => $dob, 'contact' => $contact]],
            ['upsert' => true]
        );

        if ($result->getModifiedCount() > 0 || $result->getUpsertedCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made to profile']);
        }
    } catch (Exception $e) {
        error_log('MongoDB POST Error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile: ' . $e->getMessage()]);
    }
}
?>  