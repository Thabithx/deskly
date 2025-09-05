<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';
require_once '../includes/auth.php';

// Set content type to JSON
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check if user is authenticated
        if (isset($_SESSION['user_id'])) {
            $userId = getCurrentUserId();
            $isAdmin = $_SESSION['is_admin'] ?? false;
            
            if ($isAdmin) {
                // Admin can see all orders
                $sql = "SELECT o.*, u.first_name, u.last_name, u.email 
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC";
                $stmt = executeQuery($pdo, $sql);
            } else {
                // Regular user can only see their orders
                $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
                $stmt = executeQuery($pdo, $sql, [$userId]);
            }
            
            if (!$stmt) {
                sendJsonResponse(['success' => false, 'message' => 'Failed to fetch orders'], 500);
            }
            
            $orders = $stmt->fetchAll();
            
            // Get order items for each order
            foreach ($orders as &$order) {
                $itemsSql = "SELECT oi.*, p.name, p.price, p.image 
                             FROM order_items oi 
                             JOIN products p ON oi.product_id = p.id 
                             WHERE oi.order_id = ?";
                $itemsStmt = executeQuery($pdo, $itemsSql, [$order['id']]);
                $order['items'] = $itemsStmt ? $itemsStmt->fetchAll() : [];
            }
            
            sendJsonResponse(['success' => true, 'orders' => $orders]);
        } else {
            sendJsonResponse(['success' => false, 'message' => 'Authentication required'], 401);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create new order
        checkAuth();
        $userId = getCurrentUserId();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $shippingAddress = sanitizeInput($input['shipping_address'] ?? '');
        $billingAddress = sanitizeInput($input['billing_address'] ?? '');
        $paymentMethod = sanitizeInput($input['payment_method'] ?? '');
        $cartItems = $input['cart_items'] ?? [];
        
        if (empty($shippingAddress) || empty($billingAddress) || empty($paymentMethod) || empty($cartItems)) {
            sendJsonResponse(['success' => false, 'message' => 'Missing required order information'], 400);
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // Calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $productSql = "SELECT price FROM products WHERE id = ? AND status = 'active'";
                $productStmt = executeQuery($pdo, $productSql, [$item['product_id']]);
                
                if (!$productStmt || $productStmt->rowCount() === 0) {
                    throw new Exception("Product not found: " . $item['product_id']);
                }
                
                $product = $productStmt->fetch();
                $total += $product['price'] * $item['quantity'];
            }
            
            // Create order
            $orderSql = "INSERT INTO orders (user_id, total_amount, shipping_address, billing_address, payment_method, status, created_at) 
                         VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            $orderStmt = executeQuery($pdo, $orderSql, [$userId, $total, $shippingAddress, $billingAddress, $paymentMethod]);
            
            if (!$orderStmt) {
                throw new Exception("Failed to create order");
            }
            
            $orderId = getLastInsertId($pdo);
            
            // Add order items
            foreach ($cartItems as $item) {
                $productSql = "SELECT price, stock FROM products WHERE id = ? AND status = 'active'";
                $productStmt = executeQuery($pdo, $productSql, [$item['product_id']]);
                $product = $productStmt->fetch();
                
                // Check stock
                if ($product['stock'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for product: " . $item['product_id']);
                }
                
                // Insert order item
                $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                $itemStmt = executeQuery($pdo, $itemSql, [$orderId, $item['product_id'], $item['quantity'], $product['price']]);
                
                if (!$itemStmt) {
                    throw new Exception("Failed to add order item");
                }
                
                // Update product stock
                $updateStockSql = "UPDATE products SET stock = stock - ? WHERE id = ?";
                $updateStockStmt = executeQuery($pdo, $updateStockSql, [$item['quantity'], $item['product_id']]);
                
                if (!$updateStockStmt) {
                    throw new Exception("Failed to update product stock");
                }
            }
            
            // Clear user's cart
            $clearCartSql = "DELETE FROM cart WHERE user_id = ?";
            executeQuery($pdo, $clearCartSql, [$userId]);
            
            // Commit transaction
            $pdo->commit();
            
            logActivity($pdo, $userId, 'create_order', "Created order ID: $orderId");
            sendJsonResponse(['success' => true, 'message' => 'Order created successfully', 'orderId' => $orderId]);
            
        } catch (Exception $e) {
            // Rollback transaction
            $pdo->rollback();
            throw $e;
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update order status (admin only)
        checkAdminAuth();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $orderId = (int)($input['order_id'] ?? 0);
        $status = sanitizeInput($input['status'] ?? '');
        
        if ($orderId <= 0 || empty($status)) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid order ID or status'], 400);
        }
        
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid status'], 400);
        }
        
        $sql = "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?";
        $stmt = executeQuery($pdo, $sql, [$status, $orderId]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to update order'], 500);
        }
        
        logActivity($pdo, getCurrentUserId(), 'update_order', "Updated order ID: $orderId to status: $status");
        sendJsonResponse(['success' => true, 'message' => 'Order updated successfully']);
    } else {
        sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (Exception $e) {
    error_log("Orders API error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
}
?>
