<?php $currentPage = basename($_SERVER['SCRIPT_NAME']);?>
<header>
    <div class="nav-container">
        <div class="logo">
            <a href="/deskly/index.php"><img src="/deskly/frontend/assets/images/deskly_logo.png" alt="logo"></a>
        </div>
        <?php if ($currentPage !== 'store.php'): ?>
            <div class="nav-links">
                <a href="/deskly/frontend/pages/store.php" class="<?php echo ($currentPage === 'store.php') ? 'active' : ''; ?>">STORE</a>
                <a href="/deskly/frontend/pages/accessories.php" class="<?php echo ($currentPage === 'accessories.php') ? 'active' : ''; ?>">ACCESSORIES</a>
                <a href="/deskly/frontend/pages/ergonomics.php" class="<?php echo ($currentPage === 'ergonomics.php') ? 'active' : ''; ?>">ERGONOMICS</a>
                <a href="/deskly/frontend/pages/wellness.php" class="<?php echo ($currentPage === 'wellness.php') ? 'active' : ''; ?>">WELLNESS</a>
                <a href="/deskly/frontend/pages/decor.php" class="<?php echo ($currentPage === 'decor.php') ? 'active' : ''; ?>">DECOR</a>
                <a href="/deskly/frontend/pages/contact.php" class="<?php echo ($currentPage === 'contact.php') ? 'active' : ''; ?>">SUPPORT</a>
            </div>
        <?php else: ?>
            <div class="nav-search">
                <form action="/deskly/frontend/pages/store.php" method="get">
                    <input type="text" name="q" placeholder="Search products..." class="search-input"a>
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>
        <?php endif; ?>
        <div class="nav-actions">
            <?php if ($currentPage !== 'store.php'): ?>
                <a href="/deskly/frontend/pages/store.php"><img src="/deskly/frontend/assets/images/search.svg" alt="search" width="25" height="25"></a>
            <?php endif; ?>
            <a href="/deskly/frontend/pages/cart.php"><img src="/deskly/frontend/assets/images/bag.svg" alt="bag" width="23" height="23"></a>
            <a href="/deskly/frontend/pages/profile.php"><img src="/deskly/frontend/assets/images/profile.svg" alt="profile" width="29" height="29"></a>
        </div>
   </div>
</header>

