<?php
session_start();
require_once '../backend/includes/db.php';
require_once '../backend/includes/helpers.php';

// Redirect if already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $sql = "SELECT id, first_name, last_name, email, password, is_admin FROM users WHERE email = ? AND is_admin = 1 AND status = 'active'";
        $stmt = executeQuery($pdo, $sql, [$email]);
        
        if ($stmt && $stmt->rowCount() > 0) {
            $user = $stmt->fetch();
            
            if (verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['is_admin'] = true;
                
                // Update last login
                $updateSql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                executeQuery($pdo, $updateSql, [$user['id']]);
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Deskly</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h1>Deskly Admin</h1>
            <h2>Login</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="back-link">
                <a href="../frontend/index.html">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
