<?php 
                        $images = json_decode($product['image_urls'], true);
                        $Image1 = $images[0];
                        $Image2 = $images[1];
                        $Image3 = $images[2];
                    ?>
                    <div class="product-card" onclick="window.location.href='product.php?id=<?php echo $product['product_id'] ?>'">
                        <img src="http://localhost<?php echo $Image1; ?>" alt="">
                        <div class="product-card-text">
                            <h1><?php echo $product['name'] ?></h1>
                            <p>$&nbsp<?php echo $product['price'] ?></p>
                            <button>Shop</button>
                        </div>
                    </div>