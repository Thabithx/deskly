<?php
session_start();
include __DIR__.'/../controllers/db.php';
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit;
}

$conn = dbConnect();

$val = $conn->prepare("SELECT * FROM users WHERE email = ?");
$val->bind_param("s", $email);
$val->execute();
$result = $val->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        
        if ($user['role'] == "user") {
            echo json_encode(['success' => true, 'redirect' => '/deskly/']);
        } else {
            echo json_encode(['success' => true, 'redirect' => '/deskly/admin/orders.php']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No account found with that email']);
}

$val->close();
$conn->close();
?>
