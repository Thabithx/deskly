<?php
require __DIR__ . '/../backend/controllers/db.php';
$conn = dbConnect();

// Get product ID
$productId = $_GET['id'] ?? null;
if (!$productId) {
    echo "<script>alert('Invalid product ID'); window.location.href='/deskly/admin/products.php';</script>";
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<script>alert('Product not found'); window.location.href='/deskly/admin/products.php';</script>";
    exit;
}

// Decode images
$images = json_decode($product['image_urls'], true) ?: [];
$categories = ['Ergonomics', 'Decor', 'Accessories', 'Wellness'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product - Deskly</title>
<link rel="stylesheet" href="/deskly/admin/src/css/admin.css">

</head>
<body>
<?php include __DIR__ . '/src/includes/header.php'; ?>

<div class="container">
    <h1>Edit Product</h1>

    <form id="edit-product-form" method="POST" enctype="multipart/form-data" action="/deskly/backend/api/updateproduct.php">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <input type="hidden" id="remaining_images" name="remaining_images" value="<?= htmlspecialchars(json_encode($images)) ?>">

        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Price ($)</label>
            <input type="number" name="price" id="price" step="0.01" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $product['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Existing Images</label>
            <div class="preview-container" id="existing-images">
                <?php foreach ($images as $img): ?>
                    <div class="preview-img" data-src="<?= htmlspecialchars($img) ?>" style="background-image: url('<?= htmlspecialchars($img) ?>'); background-size: cover; background-position: center;"></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="images">Upload New Images (optional)</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*">
            <div class="preview-container" id="preview-images"></div>
        </div>

        <button type="submit" class="btn-primary">Update Product</button>
        <a href="/deskly/admin/products.php" class="btn-secondary">Cancel</a>
    </form>
</div>

<?php include __DIR__ . '/src/includes/footer.php'; ?>

<script src="/deskly/admin/src/js/admin.js"></script>

</body>
</html>
