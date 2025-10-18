<?php
require '../../config.php'; 
header('Content-Type: application/json');

$sql = "
    SELECT 
        o.order_id,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.email,
        u.phone,
        u.city,
        u.country,
        o.order_date,
        o.total_amount,
        o.category,
        o.status
    FROM orders o
    INNER JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Database query failed"]);
    exit;
}

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode(["success" => true, "orders" => $orders]);
$conn->close();
