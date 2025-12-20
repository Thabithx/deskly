<?php
require __DIR__ . '/../controllers/db.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId   = $_POST['product_id'] ?? null;
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = $_POST['price'] ?? 0;
    $category    = trim($_POST['category'] ?? '');
    $remaining   = json_decode($_POST['remaining_images'] ?? '[]', true);
    $imageUrls   = $remaining ?: [];

    if (!$productId || empty($name) || empty($description) || empty($category)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    // Ensure upload directory exists
    $uploadDir = __DIR__ . '/../../uploads/products/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Handle new uploads
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
                $targetPath = $uploadDir . $fileName;
                $webPath = '/deskly/uploads/products/' . $fileName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $imageUrls[] = $webPath;
                }
            }
        }
    }

    $imageJson = json_encode($imageUrls);

    // Update product
    $update = $conn->prepare("
        UPDATE products 
        SET name = ?, description = ?, price = ?, category = ?, image_urls = ? 
        WHERE product_id = ?
    ");
    $update->bind_param("ssdssi", $name, $description, $price, $category, $imageJson, $productId);

    if ($update->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='/deskly/admin/products.php';</script>";
    } else {
        echo "<script>alert('Failed to update product.'); window.history.back();</script>";
    }

    $update->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request method.'); window.history.back();</script>";
}
?>
