<?php
require __DIR__ . '/../controllers/db.php';
$conn = dbConnect();

$stmt = $conn->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($messages);
?>
