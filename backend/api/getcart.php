<?php

session_start();
include __DIR__ . '/../controllers/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "redirect" => "/deskly/frontend/pages/login.php"]);
    exit;
}

$userId = $_SESSION['user_id'];

$conn = dbConnect();

$query = "
    SELECT c.product_id, c.quantity, p.name, p.price, p.image_urls
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";

$cartdata = $conn->prepare($query);
$cartdata->bind_param("i", $userId);
$cartdata->execute();
$result = $cartdata->get_result();

$cartItems = [];
while($row = $result->fetch_assoc()) {
    $images = json_decode($row['image_urls'], true);
    $cartItems[] = [
        "product_id" => $row['product_id'],
        "name" => $row['name'],
        "price" => $row['price'],
        "quantity" => $row['quantity'],
        "image" => $images[0] ?? ""
    ];
}

echo json_encode(["success" => true, "items" => $cartItems]);
exit;
