<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

$userId = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$productId = intval($data['product_id'] ?? 0);

if($productId <= 0){
    echo json_encode(["success"=>false,"message"=>"Invalid product"]);
    exit;
}

$conn = dbConnect();
$item = $conn->prepare("DELETE FROM cart WHERE user_id=? AND product_id=?");
$item->bind_param("ii", $userId, $productId);
$item->execute();

echo json_encode(["success"=>true,"message"=>"Product removed"]);
exit;
