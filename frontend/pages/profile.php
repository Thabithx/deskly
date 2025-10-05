<?php
include __DIR__ . '/../../backend/controllers/db.php';

// ===== FETCH DATA FROM DB =====
$users = fetchUsers();
$user = $users[0]; // For now, take top user (later replace with session-based fetch)

$featuredProducts = fetchFeaturedProducts(6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($user['first_name']) ?>'s Profile - Deskly</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>

  <?php include __DIR__ . '/../includes/header.php'; ?>

  <main class="profile-page">
    <!-- ===== USER INFO ===== -->
    <section class="profile-overview">
      <div class="profile-card">
        <div class="profile-left">
          <div class="profile-img">
            <img src=<?= htmlspecialchars($user['profile_pic']); ?> alt="Profile Picture" id="profileImage">
            <label for="uploadImage" class="upload-overlay">
              <span>Update</span>
            </label>
            <input type="file" id="uploadImage" hidden>
          </div>
        </div>

        <div class="profile-right">
          <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
          <p><i class="fa fa-envelope"></i> <?= htmlspecialchars($user['email']); ?></p>
          <p><i class="fa fa-phone"></i> <?= htmlspecialchars($user['phone']); ?></p>
          <p><i class="fa fa-calendar"></i> Member since: <?= date('F Y', strtotime($user['created_at'])); ?></p>
          <button class="edit-profile-btn"><i class="fa fa-pen"></i> Edit Profile</button>
           <a href="/deskly/logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Sign Out</a>
        </div>
      </div>
    </section>

    <!-- ===== SHIPPING ADDRESS ===== -->
    <section class="shipping-address">
      <div class="section-header">
        <h3><i class="fa fa-location-dot"></i> Shipping Address</h3>
        <button class="edit-btn"><i class="fa fa-pen"></i> Edit</button>
      </div>
      <div class="address-card">
        <p><strong><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></p>
        <p><?= htmlspecialchars($user['address']); ?></p>
        <?php if (!empty($user['landmark'])): ?>
          <p><?= htmlspecialchars($user['landmark']); ?></p>
        <?php endif; ?>
        <p><?= htmlspecialchars($user['city']); ?>, <?= htmlspecialchars($user['postcode']); ?></p>
        <p><?= htmlspecialchars($user['country']); ?></p>
        <p><i class="fa fa-phone"></i> <?= htmlspecialchars($user['phone']); ?></p>
      </div>
    </section>

    <!-- ===== MY ORDERS ===== -->
    <section class="orders-section">
      <h3><i class="fa fa-box"></i> My Orders</h3>
      <div class="order-tabs">
        <button class="active">All Orders</button>
        <button>Pending</button>
        <button>Delivered</button>
        <button>Cancelled</button>
      </div>

      <div class="orders-grid">
        <p style="color: #777; text-align:center;">No orders found for <?= htmlspecialchars($user['first_name']); ?> yet.</p>
      </div>
    </section>
    <?php include __DIR__ . '/../includes/title.php'; ?>
    <?php renderTitle("You may also love these.","perfectly paired just for you.") ?>
  </main>
    
    <?php include __DIR__ . '/../includes/featured.php'; ?>
    <?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
