<?php
session_start();
include __DIR__ . '/../controllers/db.php';

header('Content-Type: application/json'); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "redirect" => "/deskly/frontend/pages/login.php"]);
    exit;
}

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$productId = intval($data['product_id'] ?? 0);
$quantity  = intval($data['quantity'] ?? 1);

if ($productId == 0) {
    echo json_encode(["success" => false, "message" => "Invalid product"]);
    exit;
}

$conn = dbConnect();

$check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
$check->bind_param("ii", $userId, $productId); 
$check->execute();
$result = $check->get_result();
$existing = $result->fetch_assoc();


if ($existing) {
    $newQty = $existing['quantity'] + $quantity;
    $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $update->bind_param("iii", $newQty, $userId, $productId);
    $update->execute();
} else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $userId, $productId, $quantity);
    $insert->execute();
}


echo json_encode(["success" => true, "message" => "Product added to cart"]);
exit;
?>