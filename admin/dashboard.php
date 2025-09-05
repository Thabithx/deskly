<?php
session_start();
require_once '../backend/includes/db.php';
require_once '../backend/includes/helpers.php';

// Check admin authentication
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

// Get dashboard statistics
$stats = [];

// Total users
$userSql = "SELECT COUNT(*) as count FROM users WHERE status = 'active'";
$userStmt = executeQuery($pdo, $userSql);
$stats['users'] = $userStmt ? $userStmt->fetch()['count'] : 0;

// Total products
$productSql = "SELECT COUNT(*) as count FROM products WHERE status = 'active'";
$productStmt = executeQuery($pdo, $productSql);
$stats['products'] = $productStmt ? $productStmt->fetch()['count'] : 0;

// Total orders
$orderSql = "SELECT COUNT(*) as count FROM orders";
$orderStmt = executeQuery($pdo, $orderSql);
$stats['orders'] = $orderStmt ? $orderStmt->fetch()['count'] : 0;

// Total revenue
$revenueSql = "SELECT SUM(total_amount) as total FROM orders WHERE status = 'delivered'";
$revenueStmt = executeQuery($pdo, $revenueSql);
$stats['revenue'] = $revenueStmt ? $revenueStmt->fetch()['total'] : 0;

// Recent orders
$recentOrdersSql = "SELECT o.*, u.first_name, u.last_name FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC LIMIT 5";
$recentOrdersStmt = executeQuery($pdo, $recentOrdersSql);
$recentOrders = $recentOrdersStmt ? $recentOrdersStmt->fetchAll() : [];

// Low stock products
$lowStockSql = "SELECT * FROM products WHERE stock < 10 AND status = 'active' ORDER BY stock ASC LIMIT 5";
$lowStockStmt = executeQuery($pdo, $lowStockSql);
$lowStockProducts = $lowStockStmt ? $lowStockStmt->fetchAll() : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Deskly</title>
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
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="users.php">Users</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="faqs.php">FAQs</a></li>
            </ul>
        </nav>
        
        <main class="admin-main">
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="stat-number"><?php echo $stats['users']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="stat-number"><?php echo $stats['products']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="stat-number"><?php echo $stats['orders']; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="stat-number"><?php echo formatCurrency($stats['revenue']); ?></div>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-section">
                    <h2>Recent Orders</h2>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                                    <td><?php echo formatCurrency($order['total_amount']); ?></td>
                                    <td><span class="status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="dashboard-section">
                    <h2>Low Stock Products</h2>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><span class="low-stock"><?php echo $product['stock']; ?></span></td>
                                    <td><?php echo formatCurrency($product['price']); ?></td>
                                    <td><a href="products.php?edit=<?php echo $product['id']; ?>" class="btn btn-small">Edit</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="assets/js/admin.js"></script>
</body>
</html>
