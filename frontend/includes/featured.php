<?php $featuredProducts = fetchFeaturedProducts(5)?>
<div id="featured-div">
   <?php foreach($featuredProducts as $product){ ?>
      <?php 
         $images = json_decode($product['image_urls'], true);
         $firstImage = $images[0];
      ?>
      <div class="featured-div-products" style="background-image: url('http://localhost<?php echo $firstImage; ?>');">
         <div id="featured-products-text">
               <h1><?php echo $product['name'] ?></p>
               <p>$&nbsp<?php echo $product['price'] ?></p>
         </div>
      </div>
   <?php } ?>

</div>