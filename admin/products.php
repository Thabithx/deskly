<?php
include __DIR__.'/../backend/controllers/db.php'; 

$categories = [
    'Ergonomics' => fetchErgonomics(),
    'Decor' => fetchDecors(),
    'Accessories' => fetchAccessories(),
    'Wellness' => fetchWellness()
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Products - Deskly</title>
    <link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
</head>
<body>
    <?php include __DIR__.'/src/includes/header.php'; ?>

    <div class="container">
        <h1>Products Dashboard</h1>

        <section class="add-product">
            <h2>Add New Product</h2>
            <form id="add-product-form" method="POST" enctype="multipart/form-data" action="/deskly/backend/api/addproduct.php">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" name="price" id="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <?php foreach(array_keys($categories) as $cat): ?>
                            <option value="<?= $cat ?>"><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="images">Upload Images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" required>
                    <div id="preview-images" class="preview-images"></div>
                </div>
                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </section>

        <?php foreach($categories as $categoryName => $products): ?>
            <?php if(!empty($products)): ?>
                <section class="products-list">
                    <h2><?= $categoryName ?></h2>
                    <div class="products-grid">
                        <?php foreach($products as $product): ?>
                            <?php 
                                $images = json_decode($product['image_urls'], true);
                                $Image1 = $images[0] ?? '/deskly/frontend/assets/images/default.png';
                            ?>
                            <div class="product-card">
                                <div class="product-images">
                                    <img src="<?= htmlspecialchars($Image1) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                </div>
                                <div class="product-info">
                                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                                    <p class="description"><?= htmlspecialchars($product['description']) ?></p>
                                    <p class="price">$<?= number_format($product['price'], 2) ?></p>
                                </div>
                                <div class="product-actions">
                                    <a href="/deskly/admin/editproduct.php?id=<?= $product['product_id'] ?>" class="btn btn-edit">Edit</a>
                                    <a href="/deskly/admin/backend/controllers/delete_product.php?id=<?= $product['product_id'] ?>" class="btn btn-delete" >Delete</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endforeach; ?>

    </div>

    <?php include __DIR__.'/src/includes/footer.php'; ?>
    <script src="/deskly/admin/src/js/admin.js"></script>
</body>
</html>
