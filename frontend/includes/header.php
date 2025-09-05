<?php
$isInPagesDirectory = strpos($_SERVER['REQUEST_URI'], '/frontend/pages/') !== false;
$isInFrontendDirectory = strpos($_SERVER['REQUEST_URI'], '/frontend/') !== false && !$isInPagesDirectory;
$isInRootDirectory = !$isInFrontendDirectory && !$isInPagesDirectory;

// Set base path prefix for assets and links
if ($isInPagesDirectory) {
    $basePathPrefix = '../';
    $homePath = '../../index.php';
} elseif ($isInFrontendDirectory) {
    $basePathPrefix = '';
    $homePath = '../index.php';
} else {
    $basePathPrefix = 'frontend/';
    $homePath = 'index.php';
}

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<header>
    <div class="nav-container">
        <div class="logo">
            <a href="<?php echo $homePath;?>"><img src="<?php echo $basePathPrefix; ?>assets/images/deskly_logo.png" alt="logo"></a>
        </div>
        <div class="nav-links">
            <a href="<?php echo $basePathPrefix; ?>pages/store.php" class="<?php echo ($currentPage === 'store.php') ? 'active' : ''; ?>">STORE</a>
            <a href="<?php echo $basePathPrefix; ?>pages/accessories.php" class="<?php echo ($currentPage === 'accessories.php') ? 'active' : ''; ?>">ACCESSORIES</a>
            <a href="<?php echo $basePathPrefix; ?>pages/ergonomics.php" class="<?php echo ($currentPage === 'ergonomics.php') ? 'active' : ''; ?>">ERGONOMICS</a>
            <a href="<?php echo $basePathPrefix; ?>pages/wellness.php" class="<?php echo ($currentPage === 'wellness.php') ? 'active' : ''; ?>">WELLNESS</a>
            <a href="<?php echo $basePathPrefix; ?>pages/decor.php" class="<?php echo ($currentPage === 'decor.php') ? 'active' : ''; ?>">DECOR</a>
            <a href="<?php echo $basePathPrefix; ?>pages/contact.php" class="<?php echo ($currentPage === 'contact.php') ? 'active' : ''; ?>">SUPPORT</a>
        </div>
        <div class="nav-actions">
            <a href="<?php echo $basePathPrefix; ?>pages/store.php"><img src="<?php echo $basePathPrefix;?>assets/images/search.svg" alt="search" width="25" height="25"></a>
            <a href="<?php echo $basePathPrefix; ?>pages/cart.php"><img src="<?php echo $basePathPrefix; ?>assets/images/bag.svg" alt="bag" width="23" height="23"></a>
            <a href="<?php echo $basePathPrefix; ?>pages/login.php"><img src="<?php echo $basePathPrefix; ?>assets/images/profile.svg" alt="profile" width="29" height="29"></a>
        </div>
   </div>
</header>

