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

// Check authentication
checkAuth();

try {
    $userId = getCurrentUserId();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Get user's cart
        $sql = "SELECT c.*, p.name, p.price, p.image FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ? AND p.status = 'active'";
        $stmt = executeQuery($pdo, $sql, [$userId]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to fetch cart'], 500);
        }
        
        $cartItems = $stmt->fetchAll();
        sendJsonResponse(['success' => true, 'cart' => $cartItems]);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Add item to cart
        $input = json_decode(file_get_contents('php://input'), true);
        
        $productId = (int)($input['product_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 1);
        
        if ($productId <= 0 || $quantity <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid product ID or quantity'], 400);
        }
        
        // Check if product exists and is active
        $productSql = "SELECT id, stock FROM products WHERE id = ? AND status = 'active'";
        $productStmt = executeQuery($pdo, $productSql, [$productId]);
        
        if (!$productStmt || $productStmt->rowCount() === 0) {
            sendJsonResponse(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        $product = $productStmt->fetch();
        
        // Check if item already exists in cart
        $checkSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $checkStmt = executeQuery($pdo, $checkSql, [$userId, $productId]);
        
        if ($checkStmt && $checkStmt->rowCount() > 0) {
            // Update existing item
            $existingItem = $checkStmt->fetch();
            $newQuantity = $existingItem['quantity'] + $quantity;
            
            if ($newQuantity > $product['stock']) {
                sendJsonResponse(['success' => false, 'message' => 'Not enough stock available'], 400);
            }
            
            $updateSql = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?";
            $updateStmt = executeQuery($pdo, $updateSql, [$newQuantity, $existingItem['id']]);
            
            if (!$updateStmt) {
                sendJsonResponse(['success' => false, 'message' => 'Failed to update cart'], 500);
            }
        } else {
            // Add new item
            if ($quantity > $product['stock']) {
                sendJsonResponse(['success' => false, 'message' => 'Not enough stock available'], 400);
            }
            
            $insertSql = "INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())";
            $insertStmt = executeQuery($pdo, $insertSql, [$userId, $productId, $quantity]);
            
            if (!$insertStmt) {
                sendJsonResponse(['success' => false, 'message' => 'Failed to add to cart'], 500);
            }
        }
        
        logActivity($pdo, $userId, 'add_to_cart', "Added product ID: $productId, quantity: $quantity");
        sendJsonResponse(['success' => true, 'message' => 'Item added to cart']);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update cart item quantity
        $input = json_decode(file_get_contents('php://input'), true);
        
        $cartId = (int)($input['cart_id'] ?? 0);
        $quantity = (int)($input['quantity'] ?? 0);
        
        if ($cartId <= 0 || $quantity <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid cart ID or quantity'], 400);
        }
        
        // Check if cart item belongs to user
        $checkSql = "SELECT c.*, p.stock FROM cart c 
                     JOIN products p ON c.product_id = p.id 
                     WHERE c.id = ? AND c.user_id = ?";
        $checkStmt = executeQuery($pdo, $checkSql, [$cartId, $userId]);
        
        if (!$checkStmt || $checkStmt->rowCount() === 0) {
            sendJsonResponse(['success' => false, 'message' => 'Cart item not found'], 404);
        }
        
        $cartItem = $checkStmt->fetch();
        
        if ($quantity > $cartItem['stock']) {
            sendJsonResponse(['success' => false, 'message' => 'Not enough stock available'], 400);
        }
        
        $updateSql = "UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?";
        $updateStmt = executeQuery($pdo, $updateSql, [$quantity, $cartId]);
        
        if (!$updateStmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to update cart'], 500);
        }
        
        logActivity($pdo, $userId, 'update_cart', "Updated cart item ID: $cartId, quantity: $quantity");
        sendJsonResponse(['success' => true, 'message' => 'Cart updated']);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Remove item from cart
        $cartId = (int)($_GET['id'] ?? 0);
        
        if ($cartId <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid cart ID'], 400);
        }
        
        // Check if cart item belongs to user
        $checkSql = "SELECT id FROM cart WHERE id = ? AND user_id = ?";
        $checkStmt = executeQuery($pdo, $checkSql, [$cartId, $userId]);
        
        if (!$checkStmt || $checkStmt->rowCount() === 0) {
            sendJsonResponse(['success' => false, 'message' => 'Cart item not found'], 404);
        }
        
        $deleteSql = "DELETE FROM cart WHERE id = ?";
        $deleteStmt = executeQuery($pdo, $deleteSql, [$cartId]);
        
        if (!$deleteStmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to remove from cart'], 500);
        }
        
        logActivity($pdo, $userId, 'remove_from_cart', "Removed cart item ID: $cartId");
        sendJsonResponse(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (Exception $e) {
    error_log("Cart API error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
}
?>
