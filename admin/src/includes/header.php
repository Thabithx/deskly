<?php $currentPage = basename($_SERVER['SCRIPT_NAME']);?>
<header>
    <div class="nav-container">
        <div class="logo">
            <a href="/deskly/admin/orders.php"><img src="/deskly/frontend/assets/images/deskly_logo.png" alt="logo"></a>
        </div>
            <div class="nav-links">
                <a href="/deskly/admin/orders.php" class="<?php echo ($currentPage === 'store.php') ? 'active' : ''; ?>">ORDERS</a>
                <a href="/deskly/admin/users.php" class="<?php echo ($currentPage === 'store.php') ? 'active' : ''; ?>">USERS</a>
                <a href="/deskly/admin/products.php" class="<?php echo ($currentPage === 'accessories.php') ? 'active' : ''; ?>">PRODUCTS</a>
                <a href="/deskly/admin/faqs.php" class="<?php echo ($currentPage === 'ergonomics.php') ? 'active' : ''; ?>">FAQs</a>
            </div>
        <div class="nav-actions">
            <a href="/deskly/admin/profile.php"><img src="/deskly/frontend/assets/images/profile.svg" alt="profile" width="29" height="29"></a>
        </div>
   </div>
</header>

