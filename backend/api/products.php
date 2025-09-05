<?php
require_once '../includes/db.php';
require_once '../includes/helpers.php';

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
        // Get single product
        if (isset($_GET['id'])) {
            $productId = (int)$_GET['id'];
            $sql = "SELECT * FROM products WHERE id = ? AND status = 'active'";
            $stmt = executeQuery($pdo, $sql, [$productId]);
            
            if (!$stmt || $stmt->rowCount() === 0) {
                sendJsonResponse(['success' => false, 'message' => 'Product not found'], 404);
            }
            
            $product = $stmt->fetch();
            sendJsonResponse(['success' => true, 'product' => $product]);
        } else {
            // Get all products
            $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC";
            $stmt = executeQuery($pdo, $sql);
            
            if (!$stmt) {
                sendJsonResponse(['success' => false, 'message' => 'Failed to fetch products'], 500);
            }
            
            $products = $stmt->fetchAll();
            sendJsonResponse(['success' => true, 'products' => $products]);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create new product (admin only)
        require_once '../includes/auth.php';
        checkAdminAuth();
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = sanitizeInput($input['name'] ?? '');
        $description = sanitizeInput($input['description'] ?? '');
        $price = (float)($input['price'] ?? 0);
        $category = sanitizeInput($input['category'] ?? '');
        $stock = (int)($input['stock'] ?? 0);
        $image = $input['image'] ?? '';
        
        if (empty($name) || empty($description) || $price <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Name, description, and price are required'], 400);
        }
        
        $sql = "INSERT INTO products (name, description, price, category, stock, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
        $stmt = executeQuery($pdo, $sql, [$name, $description, $price, $category, $stock, $image]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to create product'], 500);
        }
        
        $productId = getLastInsertId($pdo);
        logActivity($pdo, getCurrentUserId(), 'create_product', "Created product: $name");
        
        sendJsonResponse(['success' => true, 'message' => 'Product created successfully', 'productId' => $productId]);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update product (admin only)
        require_once '../includes/auth.php';
        checkAdminAuth();
        
        $input = json_decode(file_get_contents('php://input'), true);
        $productId = (int)($input['id'] ?? 0);
        
        if ($productId <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid product ID'], 400);
        }
        
        $name = sanitizeInput($input['name'] ?? '');
        $description = sanitizeInput($input['description'] ?? '');
        $price = (float)($input['price'] ?? 0);
        $category = sanitizeInput($input['category'] ?? '');
        $stock = (int)($input['stock'] ?? 0);
        $image = $input['image'] ?? '';
        
        $sql = "UPDATE products SET name = ?, description = ?, price = ?, category = ?, stock = ?, image = ?, updated_at = NOW() WHERE id = ?";
        $stmt = executeQuery($pdo, $sql, [$name, $description, $price, $category, $stock, $image, $productId]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to update product'], 500);
        }
        
        logActivity($pdo, getCurrentUserId(), 'update_product', "Updated product ID: $productId");
        sendJsonResponse(['success' => true, 'message' => 'Product updated successfully']);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Delete product (admin only)
        require_once '../includes/auth.php';
        checkAdminAuth();
        
        $productId = (int)($_GET['id'] ?? 0);
        
        if ($productId <= 0) {
            sendJsonResponse(['success' => false, 'message' => 'Invalid product ID'], 400);
        }
        
        $sql = "UPDATE products SET status = 'deleted', updated_at = NOW() WHERE id = ?";
        $stmt = executeQuery($pdo, $sql, [$productId]);
        
        if (!$stmt) {
            sendJsonResponse(['success' => false, 'message' => 'Failed to delete product'], 500);
        }
        
        logActivity($pdo, getCurrentUserId(), 'delete_product', "Deleted product ID: $productId");
        sendJsonResponse(['success' => true, 'message' => 'Product deleted successfully']);
    } else {
        sendJsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
    }
} catch (Exception $e) {
    error_log("Products API error: " . $e->getMessage());
    sendJsonResponse(['success' => false, 'message' => 'Internal server error'], 500);
}
?>
