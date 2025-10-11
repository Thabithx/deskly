<?php
session_start();
include __DIR__.'/../controllers/db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = dbConnect();

    $val = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $val->bind_param("s", $email);
    $val->execute();
    $result = $val->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            if($user['role']=="user"){
                header("Location: /deskly/");
            }
            else{
                header("Location: /deskly/admin/orders.php");
            }
            exit;
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "No account found with that email";
    }
?>
