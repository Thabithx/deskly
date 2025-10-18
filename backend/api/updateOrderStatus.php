<?php
header('Content-Type: application/json');
require __DIR__ . '/../controllers/db.php';

$conn = dbConnect();

// Get and decode JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$order_id = intval($data['order_id']);
$status = trim($data['status']);

if ($order_id <= 0 || $status === '') {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

// ✅ Update the order status in the database
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed']);
}

$stmt->close();
$conn->close();
?>
