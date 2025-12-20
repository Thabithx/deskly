<?php
header('Content-Type: application/json');
require __DIR__ . '/../controllers/db.php';
$conn = dbConnect();

//Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$answer = trim($input['answer'] ?? '');

if (!$id || empty($answer)) {
    echo json_encode(['success' => false, 'error' => 'Message ID and answer are required']);
    exit;
}

$stmt = $conn->prepare("UPDATE contact_messages SET answer = ?, status = 'Answered' WHERE id = ?");
$stmt->bind_param("si", $answer, $id);

//log input
file_put_contents('php://stderr', print_r($input, true));

if ($stmt->execute()) { 
    echo json_encode(['success' => true, 'message' => 'Reply sent successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update message']);
}

$stmt->close();
$conn->close();
?>
