<?php
session_start();
include __DIR__ . '/../controllers/db.php';

    $conn = dbConnect();

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $val = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $val->bind_param("s", $email);
    $val->execute();
    $val->store_result();

    if ($val->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $val = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
    $val->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

    if ($val->execute()) {
        $userId = $conn->insert_id;
        $_SESSION['user_id'] = $userId;
        
        echo json_encode(["status" => "success", "message" => "Registration successful."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
    }

    $val->close();
    $conn->close();

?>
