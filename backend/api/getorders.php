<?php

session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

$userId = $_SESSION['user_id'];

$conn = dbConnect();

try {
    // Fetch all orders for the user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $ordersResult = $stmt->get_result();

    $orders = [];

    while ($order = $ordersResult->fetch_assoc()) {
        // Fetch items for each order
        $itemsStmt = $conn->prepare("
            SELECT oi.*, p.name, p.image_urls 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.product_id 
            WHERE oi.order_id = ?
        ");
        $itemsStmt->bind_param("i", $order['order_id']);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();

        $items = [];
        while ($item = $itemsResult->fetch_assoc()) {
            $image = json_decode($item['image_urls'])[0] ?? '';
            $items[] = [
                "product_id" => $item['product_id'],
                "name" => $item['name'],
                "quantity" => $item['quantity'],
                "price" => $item['price'],
                "image" => $image
            ];
        }
        $itemsStmt->close();

        $orders[] = [
            "order_id" => $order['order_id'],
            "status" => $order['status'],
            "order_date" => $order['order_date'],
            "estimated_delivery" => $order['estimated_delivery'],
            "total_amount" => (float)$order['total_amount'],
            "items" => $items
        ];
    }

    echo json_encode(["success" => true, "orders" => $orders]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
