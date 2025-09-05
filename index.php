<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deskly - Home</title>
    <link rel="stylesheet" href="frontend/assets/css/styles.css?">
</head>
<body>
    <?php include __DIR__ . '/frontend/includes/header.php'; ?>

    <div id="banner-bg">
        <div id="banner-text">
            <h2>Healthy Desk,</h2>
            <h2 style="color: rgb(58, 58, 58)">Healthy Mind.</h2>
            <a href="frontend/pages/store.php"><button id="banner-button">SHOP</button></a>
        </div>
    </div>
    <div id="category-div">
        <div class="category-card">
            <img src="frontend/assets/images/ergonomics.png" alt="">
        </div>
        <div class="category-card">
            <img src="frontend/assets/images/wellness.png" alt="">
        </div>
        <div class="category-card">
            <img src="frontend/assets/images/decors.png" alt="">
        </div>
        <div class="category-card">
            <img src="frontend/assets/images/accessories.png" alt="">
        </div>
    </div>

    <div style="margin: 20px 15px;">
        <p style="font-weight:700; font-size: 20px; margin: 10px 0;">Featured</p>
        <div id="featured-products"></div>
    </div>`

    <?php include __DIR__ . '/frontend/includes/footer.php'; ?>

    <script src="frontend/assets/js/script.js"></script>
</body>
</html>
