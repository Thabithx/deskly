<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>
    <?php include __DIR__ . '/../../backend/controllers/db.php'; ?>
    <?php 
    $Products = fetchErgonomics();?>

    <div id="store-page">
        <div>
            <div id="productsGrid">
                <?php foreach($Products as $product){ ?>
                    <?php 
                        $images = json_decode($product['image_urls'], true);
                        $Image1 = $images[0];
                        $Image2 = $images[1];
                        $Image3 = $images[2];
                    ?>
                    <div class="product-card" onclick="window.location.href='product.php?id=<?php echo $product['id'] ?>'">
                        <img src="http://localhost<?php echo $Image1; ?>" alt="">
                        <div class="product-card-text">
                            <h1><?php echo $product['name'] ?></p>
                            <p>$&nbsp<?php echo $product['price'] ?></p>
                            <button>Shop</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="/deskly/frontend/assets/js/script.js"></script>



</body>
</html>
