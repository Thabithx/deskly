<?php
// Reusable product card include
// Usage:
// include __DIR__ . '/productCard.php';
// renderProductCard([ 'id' => 1, 'name' => 'Product', 'price' => 129.99, 'image' => 'frontend/assets/images/sample.png' ]);

if (!function_exists('renderProductCard')) {
    function renderProductCard(array $product): void {
        $id = htmlspecialchars($product['id'] ?? '');
        $name = htmlspecialchars($product['name'] ?? 'Product Name');
        $price = isset($product['price']) ? number_format((float)$product['price'], 2) : '0.00';
        $image = htmlspecialchars($product['image'] ?? 'frontend/assets/images/deskly_logo.png');
        $currency = htmlspecialchars($product['currency'] ?? '$');
        $url = htmlspecialchars($product['url'] ?? 'frontend/pages/product.php?id=' . $id);
?>
        <div class="product-card" data-id="<?php echo $id; ?>">
            <a href="<?php echo $url; ?>" class="product-image-link">
                <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>" class="product-image">
            </a>
            <div class="product-info">
                <a href="<?php echo $url; ?>" class="product-name"><?php echo $name; ?></a>
                <div class="product-meta">
                    <span class="product-price"><?php echo $currency . $price; ?></span>
                    <button class="add-to-cart" data-id="<?php echo $id; ?>">Add</button>
                </div>
            </div>
        </div>
<?php
    }
}
?>


