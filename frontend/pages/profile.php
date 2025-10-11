<?php
session_start();
include __DIR__ . '/../../backend/controllers/db.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: /deskly/frontend/pages/login.php");
    exit;
}
$userId = $_SESSION['user_id'];
$user = fetchUser($userId);
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
    <section class="profile-overview">
      <div class="profile-card">
        <div class="profile-left">
          <div class="profile-img">
            <img src=<?= htmlspecialchars($user['profile_pic']); ?> alt="Profile Picture" id="profileImage">
          </div>
        </div>

        <div class="profile-right">
          <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
          <p><i class="fa fa-envelope"></i> <?= htmlspecialchars($user['email']); ?></p>
          <p>
            <?php if ($user['phone']) { ?>
                <i class="fa fa-phone"></i> <?= htmlspecialchars($user['phone']); ?>
            <?php } ?>
          </p>
          <p><i class="fa fa-calendar"></i> Member since: <?= date('F Y', strtotime($user['created_at'])); ?></p>
          <a href="/deskly/frontend/pages/editprofile.php" class="edit-profile-btn"><i class="fa fa-pen"></i>Edit Profile</a>
           <a href="/deskly/backend/api/logout.php" class="logout-btn"><i class="fa fa-sign-out-alt"></i> Sign Out</a>
        </div>
      </div>
    </section>

    <section class="shipping-address">
      <div class="section-header">
        <h3><i class="fa fa-location-dot"></i> Shipping Address</h3>
        <a href="/deskly/frontend/pages/editprofile.php" class="edit-profile-btn"><i class="fa fa-pen"></i>&nbsp Edit</a>
      </div>
      <div class="address-card">
        <p><?= htmlspecialchars($user['address']); ?></p>
        <?php if (!empty($user['landmark'])): ?>
          <p><?= htmlspecialchars($user['landmark']); ?></p>
        <?php endif; ?>
        <p><?= htmlspecialchars($user['city']); ?>, <?= htmlspecialchars($user['postcode']); ?></p>
        <p><?= htmlspecialchars($user['country']); ?></p>
        <p>
          <?php if ($user['phone']) { ?>
            <i class="fa fa-phone"></i> <?= htmlspecialchars($user['phone']); ?>
          <?php } ?>
        </p>
      </div>
    </section>

    <section class="orders-section">
      <h3><i class="fa fa-box"></i> My Orders</h3>
      <div class="order-tabs">
        <button class="order-filter active" data-status="all">All Orders</button>
        <button class="order-filter" data-status="pending">Pending</button>
        <button class="order-filter" data-status="delivered">Delivered</button>
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

     <script src="/deskly/frontend/assets/js/script.js"></script>
</body>
</html>
