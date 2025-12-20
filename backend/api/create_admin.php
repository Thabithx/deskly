<?php
session_start();
include __DIR__ . '/../controllers/db.php';
header('Content-Type: application/json');

$conn = dbConnect();

//Authorization Check
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

//Check if current user is admin
$checkStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$checkStmt->bind_param("i", $_SESSION['user_id']);
$checkStmt->execute();
$checkStmt->bind_result($role);
$checkStmt->fetch();
$checkStmt->close();

if ($role !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}

//Process Input
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$password = $_POST['password'];

if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit;
}

//Check Email uniqueness
$val = $conn->prepare("SELECT id FROM users WHERE email = ?");
$val->bind_param("s", $email);
$val->execute();
$val->store_result();

if ($val->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email already registered."]);
    exit;
}
$val->close();

//Create Admin
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$adminRole = 'admin';

$insert = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$insert->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $adminRole);

if ($insert->execute()) {
    echo json_encode(["status" => "success", "message" => "Admin created successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Creation failed: " . $conn->error]);
}

$insert->close();
$conn->close();
