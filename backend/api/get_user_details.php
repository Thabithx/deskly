<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$conn = dbConnect();
$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone, address, landmark, city, postcode, country FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();
