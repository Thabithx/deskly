<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <?php include __DIR__ . '/../../backend/controllers/db.php'; ?>
    <?php
    $product = fetchSingleProduct(isset($_GET['id']) ? intval($_GET['id']) : 0);
    $images = json_decode($product['image_urls'], true);
    $image1=$images[0];
    $image2=$images[1];
    $image3=$images[2];
    ?>

    <div id="single-product-container">
        <div id="single-product">
            <div class="main-image">
                <img id="active-image" src="http://localhost<?php echo $image1; ?>" alt="">
            </div>
            <div class="single-product-details">
                <h1><?php echo $product['name'] ?></h1>
                <p>$&nbsp<?php echo $product['price'] ?></p>
                <div class="quantity">
                    <button class="minus"
                     aria-label="Decrease">&minus;</button>
                    <input type="number" class="input-box" value="1" min="1" max="10">
                    <button class="plus" aria-label="Increase">&plus;</button>
                </div>
                <button id="add-cart-btn">ADD TO CART</button>
            </div>
        </div>

        <div id="image-swiper">
            <div class="thumb active">
                <img src="http://localhost<?php echo $image1; ?>" alt="">
            </div>
            <div class="thumb">
                <img src="http://localhost<?php echo $image2; ?>" alt="">
            </div>
            <div class="thumb">
                <img src="http://localhost<?php echo $image3; ?>" alt="">
            </div>
        </div>

        <div id="single-product-description">
            <h1>Description</h1>
            <p><?php echo $product['description'] ?></p>
        </div>
    </div>

    <?php 
        include __DIR__.'/../includes/title.php';
        renderTitle("You may also love these.","perfectly paired just for you.");
    ?>

    <?php include __DIR__.'/../includes/featured.php';?>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="/deskly/frontend/assets/js/script.js"></script>
</body>
</html>
