<?php
require __DIR__ . '/../controllers/db.php';

$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = $_POST['price'] ?? 0;
    $category    = trim($_POST['category'] ?? '');
    $imageUrls   = [];

    // Validate inputs
    if (empty($name) || empty($description) || empty($category) || empty($_FILES['images']['name'][0])) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Create image upload folder if not exists
    $uploadDir = __DIR__ . '/../../uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true); // create recursively
}   
    // Handle multiple image uploads
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
            $targetFilePath = $uploadDir . $fileName;
            $webPath = '/deskly/uploads/products/' . $fileName;

            if (move_uploaded_file($tmpName, $targetFilePath)) {
                $imageUrls[] = $webPath;
            }
        }
    }

    // Convert images to JSON
    $imageJson = json_encode($imageUrls);

    // Insert product into DB
    $stmt = $conn->prepare("
        INSERT INTO products (name, description, price, category, image_urls)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssdss", $name, $description, $price, $category, $imageJson);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully!'); window.location.href='/deskly/admin/products.php';</script>";
    } else {
        echo "<script>alert('Failed to add product. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
