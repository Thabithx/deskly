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
    $Products = fetchProducts();
    
    /*$sort = $_GET['sort'] ?? 'newest';
    $category = $_GET['category'] ?? '';
    $minPrice = $_GET['minPrice'] ?? '';
    $maxPrice = $_GET['maxPrice'] ?? '';

    $Products = array_filter($Products, function($product) use ($category, $minPrice, $maxPrice) {
        $pass = true;
        if ($category !== '' && $product['category'] !== $category) {
            $pass = false;
        }
        if ($minPrice !== '' && $product['price'] < $minPrice) {
            $pass = false;
        }
        if ($maxPrice !== '' && $product['price'] > $maxPrice) {
            $pass = false;
        }
        return $pass;
    });

    usort($Products, function($a, $b) use ($sort) {
        if ($sort === 'price_asc') {
            return $a['price'] <=> $b['price'];
        } elseif ($sort === 'price_desc') {
            return $b['price'] <=> $a['price'];
        } else { 
            return $b['id'] <=> $a['id'];
        }
    });
    */?>

    <div id="store-page">
        <div id="controls">
            <form method="get" id="filter-form">
                <div id="sort-products">
                    <label for="sort">Sort:</label>
                    <select name="sort" id="sort">
                        <option value="newest" <?php if($sort=="newest") echo "selected"; ?>>Newest</option>
                        <option value="price_asc" <?php if($sort=="price_asc") echo "selected"; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php if($sort=="price_desc") echo "selected"; ?>>Price: High to Low</option>
                    </select>
                </div>

                <div id="filter-products">
                    <div>
                        <label>Category:</label>
                        <select name="category" id="category-filter">
                            <option value="" <?php if($category=="") echo "selected"; ?>>All</option>
                            <option value="Ergonomics" <?php if($category=="Ergonomics") echo "selected"; ?>>Ergonomics</option>
                            <option value="Decor" <?php if($category=="Decor") echo "selected"; ?>>Decor</option>
                            <option value="Accessories" <?php if($category=="Accessories") echo "selected"; ?>>Accessories</option>
                            <option value="Wellness" <?php if($category=="Wellness") echo "selected"; ?>>Wellness</option>
                        </select>
                    </div>
                    <div>
                        <label>Price Range:</label>
                        <input type="number" name="minPrice" value="<?php echo htmlspecialchars($minPrice) ?>" placeholder="Min"> - 
                        <input type="number" name="maxPrice" value="<?php echo htmlspecialchars($maxPrice) ?>" placeholder="Max">
                    </div>
                </div>
            </form>
        </div>


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
