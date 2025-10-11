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
$quantity = intval($data['quantity'] ?? 1);

if($productId <= 0 || $quantity < 1){
    echo json_encode(["success"=>false,"message"=>"Invalid data"]);
    exit;
}

$conn = dbConnect();
$stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?");
$stmt->bind_param("iii", $quantity, $userId, $productId);
$stmt->execute();

echo json_encode(["success"=>true]);
exit;
?>
