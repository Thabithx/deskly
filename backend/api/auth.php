<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';
require_once '../includes/auth.php';

// Set content type to JSON
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get request data
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            }
            
            $email = sanitizeInput($input['email'] ?? '');
            $password = $input['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                sendJsonResponse(['success' => false, 'message' => 'Email and password are required'], 400);
            }
            
            if (!validateEmail($email)) {
                sendJsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
            }
            
            $result = loginUser($pdo, $email, $password);
            sendJsonResponse($result);
            break;
            
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            }
            
            $firstName = sanitizeInput($input['firstName'] ?? '');
            $lastName = sanitizeInput($input['lastName'] ?? '');
            $email = sanitizeInput($input['email'] ?? '');
            $password = $input['password'] ?? '';
            $confirmPassword = $input['confirmPassword'] ?? '';
            
            if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
                sendJsonResponse(['success' => false, 'message' => 'All fields are required'], 400);
            }
            
            if (!validateEmail($email)) {
                sendJsonResponse(['success' => false, 'message' => 'Invalid email format'], 400);
            }
            
            if (strlen($password) < 6) {
                sendJsonResponse(['success' => false, 'message' => 'Password must be at least 6 characters'], 400);
            }
            
            if ($password !== $confirmPassword) {
                sendJsonResponse(['success' => false, 'message' => 'Passwords do not match'], 400);
            }
            
            $userData = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'password' => $password
            ];
            
            $result = registerUser($pdo, $userData);
            sendJsonResponse($result);
            break;
            
        case 'logout':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
            }
            
            $result = logoutUser($pdo);
            sendJsonResponse($result);
            break;
            
        case 'check':
            $user = getCurrentUser($pdo);
            if ($user) {
                sendJsonResponse(['success' => true, 'user' => $user]);
            } else {
                sendJsonResponse(['success' => false, 'message' => 'Not authenticated'], 401);
            }
            break;
            
        default:
            sendJsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
    }
} catch (Exception $e) {
    error_log("Auth API error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
}
?>
