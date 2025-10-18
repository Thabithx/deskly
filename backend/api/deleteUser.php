<?php
header('Content-Type: application/json');
ini_set('display_errors', 0); 
error_reporting(0);

require __DIR__ . '/../controllers/db.php';

$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'] ?? null;

    if ($userId && is_numeric($userId)) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        if (!$stmt) {
            echo json_encode(["success" => false, "error" => "Failed to prepare statement"]);
            exit;
        }

        $stmt->bind_param("i", $userId);
        $success = $stmt->execute();

        if ($success) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "Failed to delete user"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Invalid user ID"]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method"]);
}
