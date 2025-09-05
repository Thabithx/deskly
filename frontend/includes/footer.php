<?php
$isInPagesDirectory = strpos($_SERVER['REQUEST_URI'], '/frontend/pages/') !== false;
$isInFrontendDirectory = strpos($_SERVER['REQUEST_URI'], '/frontend/') !== false && !$isInPagesDirectory;
$isInRootDirectory = !$isInFrontendDirectory && !$isInPagesDirectory;

// Set base path prefix for assets and links
if ($isInPagesDirectory) {
    $basePathPrefix = '../';
} elseif ($isInFrontendDirectory) {
    $basePathPrefix = '';
} else {
    $basePathPrefix = 'frontend/';
}
?>
<footer>
    <div>
        <p id="subscribe">Subscribe.</p>
        <div id="email-input-div">
            <input id="email-input" type="email" placeholder="Email Address">
            <button type="submit" class="submit-arrow">&rarr;</button>
        </div>  
    </div>
    <div id="footer-links-div">
        <div class="footer-links">
            <p>EXPLORE</p>
            <a href="<?php echo $basePathPrefix; ?>pages/store.php">Store</a>
            <a href="<?php echo $basePathPrefix; ?>pages/accessories.php">Accessories</a>
            <a href="<?php echo $basePathPrefix; ?>pages/ergonomics.php">Ergonomics</a>
            <a href="<?php echo $basePathPrefix; ?>pages/wellness.php">Wellness</a>
            <a href="<?php echo $basePathPrefix; ?>pages/decor.php">Decor</a>
        </div>
        <div class="footer-links">
            <p>SUPPORT</p>
            <a href="<?php echo $basePathPrefix; ?>pages/contact.php">Contact Us</a>
            <a href="<?php echo $basePathPrefix; ?>pages/terms.php">Terms & Conditions</a>
            <a href="<?php echo $basePathPrefix; ?>pages/terms.php">Privacy Policy</a>
        </div>
        <div class="footer-links">
            <p>COMPANY</p>
            <a href="<?php echo $basePathPrefix; ?>pages/about.php">About Us</a>
            <a href="<?php echo $basePathPrefix; ?>pages/store.php">Blog</a>
            <a href="<?php echo $basePathPrefix; ?>pages/store.php">Newsletter</a>
        </div>
        <div class="footer-links">
            <p>CONNECT</p>
            <a href="https://facebook.com" target="_blank">Facebook</a>
            <a href="https://twitter.com" target="_blank">Twitter</a>
            <a href="https://instagram.com" target="_blank">Instagram</a>
            <a href="https://linkedin.com" target="_blank">LinkedIn</a>
        </div>
    </div>
    <p id="copyright">&copy; 2025 Deskly. All rights reserved.</p>
</footer>

