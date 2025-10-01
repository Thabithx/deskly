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
    $Products = fetchAccessories();?>

    <div id="store-page">
        <div>
            <div id="productsGrid">
                <?php foreach($Products as $product){ ?>
                    <?php include __DIR__.'/../includes/productCard.php'?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="/deskly/frontend/assets/js/script.js"></script>



</body>
</html>
