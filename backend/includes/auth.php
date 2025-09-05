<?php
// Authentication helper functions

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
function checkAuth() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        sendJsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
    }
}

// Check if user is admin
function checkAdminAuth() {
    checkAuth();
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        sendJsonResponse(['success' => false, 'message' => 'Admin access required'], 403);
    }
}

// Login user
function loginUser($pdo, $email, $password) {
    $sql = "SELECT id, first_name, last_name, email, password, is_admin FROM users WHERE email = ? AND status = 'active'";
    $stmt = executeQuery($pdo, $sql, [$email]);
    
    if (!$stmt || $stmt->rowCount() === 0) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    $user = $stmt->fetch();
    
    if (!verifyPassword($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid email or password'];
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['is_admin'] = $user['is_admin'];
    
    // Update last login
    $updateSql = "UPDATE users SET last_login = NOW() WHERE id = ?";
    executeQuery($pdo, $updateSql, [$user['id']]);
    
    // Log activity
    logActivity($pdo, $user['id'], 'login', 'User logged in');
    
    return [
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'firstName' => $user['first_name'],
            'lastName' => $user['last_name'],
            'email' => $user['email'],
            'isAdmin' => $user['is_admin']
        ]
    ];
}

// Register user
function registerUser($pdo, $userData) {
    // Check if email already exists
    $checkSql = "SELECT id FROM users WHERE email = ?";
    $checkStmt = executeQuery($pdo, $checkSql, [$userData['email']]);
    
    if ($checkStmt && $checkStmt->rowCount() > 0) {
        return ['success' => false, 'message' => 'Email already exists'];
    }
    
    // Hash password
    $hashedPassword = hashPassword($userData['password']);
    
    // Insert new user
    $sql = "INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = executeQuery($pdo, $sql, [
        $userData['firstName'],
        $userData['lastName'],
        $userData['email'],
        $hashedPassword
    ]);
    
    if (!$stmt) {
        return ['success' => false, 'message' => 'Registration failed'];
    }
    
    $userId = getLastInsertId($pdo);
    
    // Log activity
    logActivity($pdo, $userId, 'register', 'New user registered');
    
    return ['success' => true, 'message' => 'Registration successful'];
}

// Logout user
function logoutUser($pdo) {
    if (isset($_SESSION['user_id'])) {
        // Log activity
        logActivity($pdo, $_SESSION['user_id'], 'logout', 'User logged out');
    }
    
    // Destroy session
    session_destroy();
    
    return ['success' => true, 'message' => 'Logged out successfully'];
}

// Get current user info
function getCurrentUser($pdo) {
    if (!isLoggedIn()) {
        return null;
    }
    
    $sql = "SELECT id, first_name, last_name, email, is_admin FROM users WHERE id = ?";
    $stmt = executeQuery($pdo, $sql, [getCurrentUserId()]);
    
    if (!$stmt || $stmt->rowCount() === 0) {
        return null;
    }
    
    $user = $stmt->fetch();
    return [
        'id' => $user['id'],
        'firstName' => $user['first_name'],
        'lastName' => $user['last_name'],
        'email' => $user['email'],
        'isAdmin' => $user['is_admin']
    ];
}
?>
