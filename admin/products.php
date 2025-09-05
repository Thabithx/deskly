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
    
    if ($action === 'add' || $action === 'edit') {
        $name = sanitizeInput($_POST['name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $category = sanitizeInput($_POST['category'] ?? '');
        $stock = (int)($_POST['stock'] ?? 0);
        $productId = (int)($_POST['product_id'] ?? 0);
        
        if (empty($name) || empty($description) || $price <= 0) {
            $error = 'Name, description, and price are required';
        } else {
            // Handle file upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = uploadFile($_FILES['image'], '../backend/uploads/');
                if ($uploadResult['success']) {
                    $image = $uploadResult['filename'];
                } else {
                    $error = $uploadResult['message'];
                }
            }
            
            if (empty($error)) {
                if ($action === 'add') {
                    $sql = "INSERT INTO products (name, description, price, category, stock, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
                    $stmt = executeQuery($pdo, $sql, [$name, $description, $price, $category, $stock, $image]);
                    if ($stmt) {
                        $message = 'Product added successfully';
                    } else {
                        $error = 'Failed to add product';
                    }
                } else {
                    $updateFields = ['name = ?', 'description = ?', 'price = ?', 'category = ?', 'stock = ?'];
                    $updateValues = [$name, $description, $price, $category, $stock];
                    
                    if (!empty($image)) {
                        $updateFields[] = 'image = ?';
                        $updateValues[] = $image;
                    }
                    
                    $updateFields[] = 'updated_at = NOW()';
                    $updateValues[] = $productId;
                    
                    $sql = "UPDATE products SET " . implode(', ', $updateFields) . " WHERE id = ?";
                    $stmt = executeQuery($pdo, $sql, $updateValues);
                    if ($stmt) {
                        $message = 'Product updated successfully';
                    } else {
                        $error = 'Failed to update product';
                    }
                }
            }
        }
    } elseif ($action === 'delete') {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId > 0) {
            $sql = "UPDATE products SET status = 'deleted', updated_at = NOW() WHERE id = ?";
            $stmt = executeQuery($pdo, $sql, [$productId]);
            if ($stmt) {
                $message = 'Product deleted successfully';
            } else {
                $error = 'Failed to delete product';
            }
        }
    }
}

// Get products
$sql = "SELECT * FROM products WHERE status != 'deleted' ORDER BY created_at DESC";
$stmt = executeQuery($pdo, $sql);
$products = $stmt ? $stmt->fetchAll() : [];

// Get product for editing
$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editSql = "SELECT * FROM products WHERE id = ?";
    $editStmt = executeQuery($pdo, $editSql, [$editId]);
    $editProduct = $editStmt ? $editStmt->fetch() : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Deskly Admin</title>
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
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="page-header">
                <h2>Manage Products</h2>
                <button class="btn btn-primary" onclick="toggleProductForm()">Add New Product</button>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <div class="product-form" id="productForm" style="display: none;">
                <h3><?php echo $editProduct ? 'Edit Product' : 'Add New Product'; ?></h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit' : 'add'; ?>">
                    <?php if ($editProduct): ?>
                        <input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Product Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $editProduct ? htmlspecialchars($editProduct['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <input type="text" id="category" name="category" value="<?php echo $editProduct ? htmlspecialchars($editProduct['category']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4" required><?php echo $editProduct ? htmlspecialchars($editProduct['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="stock">Stock:</label>
                            <input type="number" id="stock" name="stock" min="0" value="<?php echo $editProduct ? $editProduct['stock'] : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Product Image:</label>
                        <input type="file" id="image" name="image" accept="image/*">
                        <?php if ($editProduct && !empty($editProduct['image'])): ?>
                            <p>Current image: <?php echo htmlspecialchars($editProduct['image']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?php echo $editProduct ? 'Update Product' : 'Add Product'; ?></button>
                        <button type="button" class="btn btn-secondary" onclick="toggleProductForm()">Cancel</button>
                    </div>
                </form>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="../backend/uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product" class="product-thumb">
                                <?php else: ?>
                                    <span class="no-image">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo formatCurrency($product['price']); ?></td>
                            <td>
                                <span class="<?php echo $product['stock'] < 10 ? 'low-stock' : ''; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td><span class="status status-<?php echo $product['status']; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                            <td>
                                <a href="?edit=<?php echo $product['id']; ?>" class="btn btn-small">Edit</a>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                </form>
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
