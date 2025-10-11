<?php
session_start();
include __DIR__ . '/../../backend/controllers/db.php';

$userId = $_SESSION['user_id'];
$user = fetchUser($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Deskly</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>

<?php include __DIR__ . '/../includes/header.php'; ?>

<main class="edit-profile-page">
 <form action="/deskly/backend/api/updateprofile.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
    <h3><i class="fa fa-user-edit"></i> Edit Profile</h2>

    <div class="profile-img">
            <img src=<?= htmlspecialchars($user['profile_pic']); ?> alt="Profile Picture" id="profileImage">
            <label for="uploadImage" class="upload-overlay">
              <span>Update</span>
            </label>
            <input type="file" id="uploadImage" name="uploadImage" hidden>
          </div>

    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>
    </div>

    <div class="form-group">
      <label>Email (read only)</label>
      <input type="email" style="color: gray" name="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>
    </div>

    <div class="form-group">
      <label>Phone</label>
      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>">
    </div>

    <h3><i class="fa fa-location-dot"></i> Shipping Address</h3>

    <div class="form-group">
      <label>Address</label>
      <input type="text" name="address" value="<?= htmlspecialchars($user['address']); ?>">
    </div>

    <div class="form-group">
      <label>Landmark</label>
      <input type="text" name="landmark" value="<?= htmlspecialchars($user['landmark']); ?>">
    </div>

    <div class="form-group">
      <label>City</label>
      <input type="text" name="city" value="<?= htmlspecialchars($user['city']); ?>">
    </div>

    <div class="form-group">
      <label>Postcode</label>
      <input type="text" name="postcode" value="<?= htmlspecialchars($user['postcode']); ?>">
    </div>

    <div class="form-group">
      <label>Country</label>
      <input type="text" name="country" value="<?= htmlspecialchars($user['country']); ?>">
    </div>

    <div class="form-actions">
      <button type="submit" class="save-btn"><i class="fa fa-save"></i> Save Changes</button>
      <a href="profile.php" class="cancel-btn"><i class="fa fa-times"></i> Cancel</a>
    </div>
  </form>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>
