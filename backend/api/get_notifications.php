<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in', 'redirect' => '/deskly/frontend/pages/login.php']);
    exit;
}

$conn = dbConnect();
$userId = $_SESSION['user_id'];

//Get user email
$emailStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$emailStmt->bind_param("i", $userId);
$emailStmt->execute();
$emailStmt->bind_result($userEmail);
$emailStmt->fetch();
$emailStmt->close();

$notifications = [];

//Fetch Message Replies
$msgStmt = $conn->prepare("SELECT id, name, message, answer, created_at FROM contact_messages WHERE email = ? AND answer IS NOT NULL ORDER BY created_at DESC");
$msgStmt->bind_param("s", $userEmail);
$msgStmt->execute();
$msgResult = $msgStmt->get_result();

while ($row = $msgResult->fetch_assoc()) {
    $notifications[] = [
        'type' => 'message_reply',
        'id' => $row['id'],
        'title' => 'Support Reply to Your Message',
        'message' => 'Admin replied: "' . substr($row['answer'], 0, 100) . (strlen($row['answer']) > 100 ? '...' : '') . '"',
        'full_answer' => $row['answer'],
        'original_question' => $row['message'],
        'date' => $row['created_at'],
        'link' => null
    ];
}
$msgStmt->close();

//Fetch Order Status Updates
$orderStmt = $conn->prepare("SELECT order_id, total_amount, status, order_date, estimated_delivery FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 10");
$orderStmt->bind_param("i", $userId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

while ($row = $orderResult->fetch_assoc()) {
    $statusMsg = '';
    switch($row['status']) {
        case 'Pending':
            $statusMsg = 'Your order is being processed';
            break;
        case 'Processing':
            $statusMsg = 'Your order is being prepared for shipment';
            break;
        case 'Shipped':
            $statusMsg = 'Your order has been shipped!';
            break;
        case 'Delivered':
            $statusMsg = 'Your order has been delivered';
            break;
        case 'Cancelled':
            $statusMsg = 'Your order has been cancelled';
            break;
        default:
            $statusMsg = 'Order status: ' . $row['status'];
    }
    
    $notifications[] = [
        'type' => 'order_update',
        'id' => $row['order_id'],
        'title' => 'Order #' . $row['order_id'] . ' - ' . $row['status'],
        'message' => $statusMsg . ' (Total: $' . number_format($row['total_amount'], 2) . ')',
        'status' => $row['status'],
        'date' => $row['order_date'],
        'link' => '/deskly/frontend/pages/trackorder.php?order_id=' . $row['order_id']
    ];
}
$orderStmt->close();

//Sort all notifications by date
usort($notifications, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'count' => count($notifications)
]);

$conn->close();
