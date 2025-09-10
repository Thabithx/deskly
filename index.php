<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deskly - Home</title>
    <link rel="stylesheet" href="/deskly/frontend/assets/css/styles.css">
</head>
<body>
    <?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);?>

    <?php include __DIR__ . '/backend/controllers/db.php'; ?>
    <?php include __DIR__ . '/frontend/includes/title.php'; ?>
    <?php include __DIR__ . '/frontend/includes/header.php'; ?>
    

    <div id="banner-bg">
        <div id="banner-text">
            <h2>Healthy Desk,</h2>
            <h2 style="color: rgb(58, 58, 58)">Healthy Mind.</h2>
            <a href="/deskly/frontend/pages/store.php"><button id="banner-button">SHOP</button></a>
        </div>
    </div>

    <div id="category-div">
        <div class="category-card">
            <a href="/deskly/frontend/pages/ergonomics.php"><img src="/deskly/frontend/assets/images/ergonomics.png" alt=""></a>
        </div>
        <div class="category-card">
            <img src="/deskly/frontend/assets/images/wellness.png" alt="">
        </div>
        <div class="category-card">
            <img src="/deskly/frontend/assets/images/decors.png" alt="">
        </div>
        <div class="category-card">
            <img src="/deskly/frontend/assets/images/accessories.png" alt="">
        </div>
    </div>

    <div id="featured">
        <?php renderTitle("Fresh picks.","Discover what’s trending today.") ?>
        <?php $featuredProducts = fetchFeaturedProducts(5); ?>
        <?php include __DIR__ . '/frontend/includes/featured.php'; ?>
    </div>

    <div id="end-banner">
        <img src="/deskly/frontend/assets/images/end-banner.png" alt="">
        <div id="end-banner-text">
            <h1>Back pain and stress don’t have to be part of your desk life.</h1>
            <p>Long hours aren’t the problem, your setup is! With the right ergonomic gear, you can stay comfortable, healthy, and focused all day. Deskly makes it easy.</p>
            <button>SHOP WELLNESS</button>
        </div>
    </div>

    <?php include __DIR__ . '/frontend/includes/footer.php'; ?>

    <script src="/deskly/frontend/assets/js/script.js"></script>
</body>
</html>
