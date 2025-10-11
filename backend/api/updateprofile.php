<?php
session_start();
include __DIR__ . '/../controllers/db.php';


$userId = $_SESSION['user_id'];

$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$address    = trim($_POST['address'] ?? '');
$landmark   = trim($_POST['landmark'] ?? '');
$city       = trim($_POST['city'] ?? '');
$postcode   = trim($_POST['postcode'] ?? '');
$country    = trim($_POST['country'] ?? '');

$profilePic = null;

if (isset($_FILES['uploadImage']) && $_FILES['uploadImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['uploadImage']['tmp_name'];
    $fileName = uniqid() . '-' . basename($_FILES['uploadImage']['name']);
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/deskly/uploads/profilepics/';
    $destPath = $uploadDir.$fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $profilePic = '/deskly/uploads/profilepics/' . $fileName;
    }
    
}

$conn = dbConnect();
if ($profilePic) {
    $sql = "UPDATE users 
            SET first_name = ?, last_name = ?, phone = ?, address = ?, 
                landmark = ?, city = ?, postcode = ?, country = ?, profile_pic = ?
            WHERE id = ?";
    $params = [$first_name, $last_name, $phone, $address, $landmark, $city, $postcode, $country, $profilePic, $userId];
} else {
    $sql = "UPDATE users 
            SET first_name = ?, last_name = ?, phone = ?, address = ?, 
                landmark = ?, city = ?, postcode = ?, country = ?
            WHERE id = ?";
    $params = [$first_name, $last_name, $phone, $address, $landmark, $city, $postcode, $country, $userId];
}
$val = $conn->prepare($sql);
$val->execute($params);

header("Location: /deskly/frontend/pages/profile.php?updated=1");
exit();
?>
