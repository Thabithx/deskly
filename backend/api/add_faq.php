<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

$conn = dbConnect();

//Authorization Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// Check if current user is admin
$checkStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$checkStmt->bind_param("i", $_SESSION['user_id']);
$checkStmt->execute();
$checkStmt->bind_result($role);
$checkStmt->fetch();
$checkStmt->close();

if ($role !== 'admin') {
    echo json_encode(["success" => false, "message" => "Access denied"]);
    exit;
}

//Process Input
$input = json_decode(file_get_contents('php://input'), true);
$question = $input['question'] ?? '';
$answer = $input['answer'] ?? '';

if (empty($question) || empty($answer)) {
    echo json_encode(["success" => false, "message" => "Question and answer are required"]);
    exit;
}

//Insert FAQ
$insert = $conn->prepare("INSERT INTO faqs (question, answer, created_at) VALUES (?, ?, NOW())");
$insert->bind_param("ss", $question, $answer);

if ($insert->execute()) {
    echo json_encode(["success" => true, "message" => "FAQ added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add FAQ: " . $conn->error]);
}

$insert->close();
$conn->close();
