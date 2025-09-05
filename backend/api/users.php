<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';
require_once '../includes/auth.php';

// Set content type to JSON
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check authentication
        checkAuth();
        $userId = getCurrentUserId();
        $isAdmin = $_SESSION['is_admin'] ?? false;
        
        if ($isAdmin) {
            // Admin can see all users
            $sql = "SELECT id, first_name, last_name, email, is_admin, status, created_at, last_login 
                    FROM users ORDER BY created_at DESC";
            $stmt = executeQuery($pdo, $sql);
            
            if (!$stmt) {
                sendJsonResponse(['success' => false, 'message' => 'Failed to fetch users'], 500);
            }
            
            $users = $stmt->fetchAll();
            sendJsonResponse(['success' => true, 'users' => $users]);
        } else {
            // Regular user can only see their own profile
            $sql = "SELECT id, first_name, last_name, email, created_at, last_login 
                    FROM users WHERE id = ?";
            $stmt = executeQuery($pdo, $sql, [$userId]);
            
            if (!$stmt || $stmt->rowCount() === 0) {
                sendJsonResponse(['success' => false, 'message' => 'User not found'], 404);
            }
            
            $user = $stmt->fetch();
            sendJsonResponse(['success' => true, 'user' => $user]);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create new user (admin only)
        checkAdminAuth();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $firstName = sanitizeInput($input['first_name'] ?? '');
        $lastName = sanitizeInput($input['last_name'] ?? '');
        $email = sanitizeInput($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $isAdmin = (bool)($input['is_admin'] ?? false);
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            sendJsonResponse(['success' => false, 'message' => 'All fields are required'], 400);
        }
        
        if (!validateEmail($email)) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
        }
        
        if (strlen($password) < 6) {
            sendJsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters'], 400);
        }
        
        // Check if email already exists
        $checkSql = "SELECT id FROM users WHERE email = ?";
        $checkStmt = executeQuery($pdo, $checkSql, [$email]);
        
        if ($checkStmt && $checkStmt->rowCount() > 0) {
            sendJsonResponse(['success' => false, 'message' => 'Email already exists'], 400);
        }
        
        $hashedPassword = hashPassword($password);
        
        $sql = "INSERT INTO users (first_name, last_name, email, password, is_admin, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'active', NOW())";
        $stmt = executeQuery($pdo, $sql, [$firstName, $lastName, $email, $hashedPassword, $isAdmin]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to create user'], 500);
        }
        
        $newUserId = getLastInsertId($pdo);
        logActivity($pdo, getCurrentUserId(), 'create_user', "Created user: $email");
        
        sendJsonResponse(['success' => true, 'message' => 'User created successfully', 'userId' => $newUserId]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update user profile
        checkAuth();
        $userId = getCurrentUserId();
        $isAdmin = $_SESSION['is_admin'] ?? false;
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Check if updating own profile or admin updating another user
        $targetUserId = (int)($input['user_id'] ?? $userId);
        
        if ($targetUserId !== $userId && !$isAdmin) {
            sendJsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $firstName = sanitizeInput($input['first_name'] ?? '');
        $lastName = sanitizeInput($input['last_name'] ?? '');
        $email = sanitizeInput($input['email'] ?? '');
        $password = $input['password'] ?? '';
        $status = sanitizeInput($input['status'] ?? '');
        
        if (empty($firstName) || empty($lastName) || empty($email)) {
            sendJsonResponse(['success' => false, 'message' => 'Name and email are required'], 400);
        }
        
        if (!validateEmail($email)) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
        }
        
        // Check if email already exists (excluding current user)
        $checkSql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $checkStmt = executeQuery($pdo, $checkSql, [$email, $targetUserId]);
        
        if ($checkStmt && $checkStmt->rowCount() > 0) {
            sendJsonResponse(['success' => false, 'message' => 'Email already exists'], 400);
        }
        
        // Build update query
        $updateFields = ['first_name = ?', 'last_name = ?', 'email = ?'];
        $updateValues = [$firstName, $lastName, $email];
        
        // Update password if provided
        if (!empty($password)) {
            if (strlen($password) < 6) {
                sendJsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters'], 400);
            }
            $updateFields[] = 'password = ?';
            $updateValues[] = hashPassword($password);
        }
        
        // Update status if admin
        if ($isAdmin && !empty($status)) {
            $validStatuses = ['active', 'inactive', 'suspended'];
            if (in_array($status, $validStatuses)) {
                $updateFields[] = 'status = ?';
                $updateValues[] = $status;
            }
        }
        
        $updateFields[] = 'updated_at = NOW()';
        $updateValues[] = $targetUserId;
        
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = executeQuery($pdo, $sql, $updateValues);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to update user'], 500);
        }
        
        logActivity($pdo, getCurrentUserId(), 'update_user', "Updated user ID: $targetUserId");
        sendJsonResponse(['success' => true, 'message' => 'User updated successfully']);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Delete user (admin only)
        checkAdminAuth();
        
        $targetUserId = (int)($_GET['id'] ?? 0);
        
        if ($targetUserId <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid user ID'], 400);
        }
        
        // Don't allow admin to delete themselves
        if ($targetUserId === getCurrentUserId()) {
            sendJsonResponse(['success' => false, 'message' => 'Cannot delete your own account'], 400);
        }
        
        // Soft delete - set status to deleted
        $sql = "UPDATE users SET status = 'deleted', updated_at = NOW() WHERE id = ?";
        $stmt = executeQuery($pdo, $sql, [$targetUserId]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
        
        logActivity($pdo, getCurrentUserId(), 'delete_user', "Deleted user ID: $targetUserId");
        sendJsonResponse(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (Exception $e) {
    error_log("Users API error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
}
?>
