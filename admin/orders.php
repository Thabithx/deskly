<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/../backend/controllers/db.php';

$conn = dbConnect();

$sql = "
    SELECT 
        o.order_id,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        o.order_date,
        o.total_amount,
        o.status
    FROM orders o
    INNER JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
";

$result = $conn->query($sql);
$orders = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

function formatDate($dateStr) {
    return date("M d, Y", strtotime($dateStr));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders - Deskly Admin</title>
<link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
</head>
<body>

<?php include __DIR__.'/src/includes/header.php'?>

<main class="orders-container">
    <section class="orders-header">
        <h1>ORDERS</h1>
        
    </section>

    <section class="orders-table">
        <div class="table-controls">
            <label>Show 
                <select id="entries">
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                </select>
            </label>
            <input type="text" id="searchInput" placeholder="Search...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <?php if(count($orders) === 0): ?>
                    <tr><td colspan="5">No orders found</td></tr>
                <?php else: ?>
                    <?php foreach($orders as $order): ?>
                        <tr class="clickable" data-order-id="<?= $order['order_id'] ?>">
                            <td>#<?= $order['order_id'] ?></td>
                            <td><?= $order['customer_name'] ?></td>
                            <td><?= formatDate($order['order_date']) ?></td>
                            <td>$<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="status <?= strtolower($order['status']) ?>"><?= $order['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination" id="pagination"></div>
    </section>
</main>

<?php include __DIR__.'/src/includes/footer.php'?>
<script src="/deskly/admin/src/js/admin.js"></script>

</body>
</html>
