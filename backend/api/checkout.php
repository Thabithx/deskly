<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

$conn = dbConnect();

$userId = $_SESSION['user_id'];

$conn->begin_transaction();

try {
    $cartItems = [];
    $stmt = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($product_id, $quantity);
    while ($stmt->fetch()) $cartItems[] = ['product_id'=>$product_id,'quantity'=>$quantity];
    $stmt->close();

    if (empty($cartItems)) {
        echo json_encode(["success"=>false,"message"=>"Your cart is empty."]);
        exit;
    }

    // Calculate total
    $totalAmount = 0;
    foreach ($cartItems as &$item) {
        $priceStmt = $conn->prepare("SELECT price FROM products WHERE product_id=?");
        $priceStmt->bind_param("i",$item['product_id']);
        $priceStmt->execute();
        $priceStmt->bind_result($price);
        $priceStmt->fetch();
        $priceStmt->close();
        $item['price'] = $price ?? 0;
        $totalAmount += $item['price'] * $item['quantity'];
    }

    $estimatedDelivery = date('Y-m-d H:i:s', strtotime('+3 days'));
    $orderStmt = $conn->prepare("INSERT INTO orders (user_id,total_amount,status,estimated_delivery) VALUES (?,?, 'Pending', ?)");
    $orderStmt->bind_param("ids",$userId,$totalAmount,$estimatedDelivery);
    $orderStmt->execute();
    $orderId = $orderStmt->insert_id;
    $orderStmt->close();

    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)");
    foreach ($cartItems as $item) {
        $itemStmt->bind_param("iiid",$orderId,$item['product_id'],$item['quantity'],$item['price']);
        $itemStmt->execute();
    }
    $itemStmt->close();

    $clearStmt = $conn->prepare("DELETE FROM cart WHERE user_id=?");
    $clearStmt->bind_param("i",$userId);
    $clearStmt->execute();
    $clearStmt->close();

    $conn->commit();

    echo json_encode(["success"=>true,"message"=>"Order placed successfully!"]);
} catch(Exception $e) {
    $conn->rollback();
    echo json_encode(["success"=>false,"message"=>$e->getMessage()]);
}

$conn->close();
