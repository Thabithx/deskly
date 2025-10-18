<?php
session_start();
include __DIR__ . '/../controllers/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /deskly/frontend/pages/login.php");
    exit();
}

$userId = $_SESSION['user_id'];

$conn = dbConnect(); // MySQLi connection

// Sanitize input
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$address    = trim($_POST['address'] ?? '');
$landmark   = trim($_POST['landmark'] ?? '');
$city       = trim($_POST['city'] ?? '');
$postcode   = trim($_POST['postcode'] ?? '');
$country    = trim($_POST['country'] ?? '');

$profilePic = null;

// Handle profile image upload
if (isset($_FILES['uploadImage']) && $_FILES['uploadImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['uploadImage']['tmp_name'];
    $fileName = uniqid() . '-' . basename($_FILES['uploadImage']['name']);
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/deskly/uploads/profilepics/';
    $destPath = $uploadDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $profilePic = '/deskly/uploads/profilepics/' . $fileName;
    }
}

// Update user details
if ($profilePic) {
    $sql = "UPDATE users 
            SET first_name=?, last_name=?, phone=?, address=?, 
                landmark=?, city=?, postcode=?, country=?, profile_pic=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $first_name, $last_name, $phone, $address, $landmark, $city, $postcode, $country, $profilePic, $userId);
} else {
    $sql = "UPDATE users 
            SET first_name=?, last_name=?, phone=?, address=?, 
                landmark=?, city=?, postcode=?, country=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $first_name, $last_name, $phone, $address, $landmark, $city, $postcode, $country, $userId);
}

$stmt->execute();

// Fetch user role
$roleStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$roleStmt->bind_param("i", $userId);
$roleStmt->execute();
$roleResult = $roleStmt->get_result();
$roleRow = $roleResult->fetch_assoc();
$role = $roleRow['role'] ?? 'user';

// Redirect based on role
if ($role === 'admin') {
    header("Location: /deskly/admin/profile.php");
} else {
    header("Location: /deskly/frontend/pages/profile.php");
}

exit();
?>
