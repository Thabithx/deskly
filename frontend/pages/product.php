<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main>
        <section class="product-detail">
            <div class="product-container">
                <div class="product-image">
                    <img id="productImage" src="" alt="Product Image">
                </div>
                <div class="product-info">
                    <h1 id="productName"></h1>
                    <p class="price" id="productPrice"></p>
                    <p class="description" id="productDescription"></p>
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" min="1" value="1">
                    </div>
                    <button class="add-to-cart" onclick="addToCart()">Add to Cart</button>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
