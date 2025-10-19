<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../controllers/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$userId = $_SESSION['user_id'];
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing order_id"]);
    exit();
}

$conn = dbConnect();

// Fetch order info with user details
$orderSql = "
    SELECT o.*, u.first_name, u.last_name, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.order_id = ? AND o.user_id = ?
";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bind_param("ii", $orderId, $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

if ($orderResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Order not found"]);
    exit();
}

$order = $orderResult->fetch_assoc();
$order['customer_name'] = $order['first_name'] . ' ' . $order['last_name'];

// Fetch items
$itemSql = "
    SELECT oi.*, p.name, p.image_urls 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
";
$itemStmt = $conn->prepare($itemSql);
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$itemsResult = $itemStmt->get_result();
$items = [];

while ($row = $itemsResult->fetch_assoc()) {
    $row['image_urls'] = explode(',', $row['image_urls'])[0] ?? null;
    $items[] = $row;
}

echo json_encode([
    "success" => true,
    "order" => $order,
    "items" => $items
]);
?>
