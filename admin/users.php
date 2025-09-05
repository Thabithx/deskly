<?php
session_start();
require_once '../backend/includes/db.php';
require_once '../backend/includes/helpers.php';

// Check admin authentication
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $firstName = sanitizeInput($_POST['first_name'] ?? '');
        $lastName = sanitizeInput($_POST['last_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $isAdmin = isset($_POST['is_admin']);
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $error = 'All fields are required';
        } elseif (!validateEmail($email)) {
            $error = 'Invalid email format';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            // Check if email already exists
            $checkSql = "SELECT id FROM users WHERE email = ?";
            $checkStmt = executeQuery($pdo, $checkSql, [$email]);
            
            if ($checkStmt && $checkStmt->rowCount() > 0) {
                $error = 'Email already exists';
            } else {
                $hashedPassword = hashPassword($password);
                $sql = "INSERT INTO users (first_name, last_name, email, password, is_admin, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())";
                $stmt = executeQuery($pdo, $sql, [$firstName, $lastName, $email, $hashedPassword, $isAdmin]);
                if ($stmt) {
                    $message = 'User added successfully';
                } else {
                    $error = 'Failed to add user';
                }
            }
        }
    } elseif ($action === 'update') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $firstName = sanitizeInput($_POST['first_name'] ?? '');
        $lastName = sanitizeInput($_POST['last_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $status = sanitizeInput($_POST['status'] ?? '');
        $isAdmin = isset($_POST['is_admin']);
        
        if ($userId <= 0 || empty($firstName) || empty($lastName) || empty($email)) {
            $error = 'Invalid data';
        } else {
            $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, status = ?, is_admin = ?, updated_at = NOW() WHERE id = ?";
            $stmt = executeQuery($pdo, $sql, [$firstName, $lastName, $email, $status, $isAdmin, $userId]);
            if ($stmt) {
                $message = 'User updated successfully';
            } else {
                $error = 'Failed to update user';
            }
        }
    } elseif ($action === 'delete') {
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId > 0 && $userId !== $_SESSION['user_id']) {
            $sql = "UPDATE users SET status = 'deleted', updated_at = NOW() WHERE id = ?";
            $stmt = executeQuery($pdo, $sql, [$userId]);
            if ($stmt) {
                $message = 'User deleted successfully';
            } else {
                $error = 'Failed to delete user';
            }
        } else {
            $error = 'Cannot delete your own account';
        }
    }
}

// Get users
$sql = "SELECT id, first_name, last_name, email, is_admin, status, created_at, last_login FROM users WHERE status != 'deleted' ORDER BY created_at DESC";
$stmt = executeQuery($pdo, $sql);
$users = $stmt ? $stmt->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Deskly Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Deskly Admin Dashboard</h1>
            <div class="admin-nav">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </header>
        
        <nav class="admin-sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="users.php" class="active">Users</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="page-header">
                <h2>Manage Users</h2>
                <button class="btn btn-primary" onclick="toggleUserForm()">Add New User</button>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="user-form" id="userForm" style="display: none;">
                <h3>Add New User</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_admin" value="1"> Admin User
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add User</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleUserForm()">Cancel</button>
                    </div>
                </form>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                            <td><span class="status status-<?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span></td>
                            <td><?php echo $user['last_login'] ? date('M j, Y', strtotime($user['last_login'])) : 'Never'; ?></td>
                            <td>
                                <button class="btn btn-small" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
