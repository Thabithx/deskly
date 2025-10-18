<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../backend/controllers/db.php'; // DB connection
$conn = dbConnect();

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    die("Invalid order ID");
}

// Fetch order + user info
$sql_order = "
    SELECT 
        o.order_id,
        o.order_date,
        o.total_amount,
        o.status,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.email,
        u.phone,
        u.address,
        u.city,
        u.postcode,
        u.country
    FROM orders o
    INNER JOIN users u ON o.user_id = u.id
    WHERE o.order_id = $order_id
    LIMIT 1
";
$order_result = $conn->query($sql_order);
if (!$order_result || $order_result->num_rows === 0) {
    die("Order not found");
}
$order = $order_result->fetch_assoc();

// Fetch order items + product info
$sql_items = "
    SELECT 
        p.name,
        p.category,
        p.price,
        oi.quantity,
        (oi.price * oi.quantity) AS total_price
    FROM order_items oi
    INNER JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = $order_id
";
$items_result = $conn->query($sql_items);
$order_items = [];
if ($items_result) {
    while ($row = $items_result->fetch_assoc()) {
        $order_items[] = $row;
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
<title>Order Details - #<?= $order['order_id'] ?></title>
<link rel="stylesheet" href="/deskly/admin/src/css/admin.css">
<style>
/* Global */
body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    background-color: #f5f5f7;
    color: #1d1d1f;
    margin: 0;
}
h1, h2 {
    margin: 0 0 20px 0;
    font-weight: 600;
}
main.order-details-container {
    max-width: 1100px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
}

/* Sections */
section {
    margin-bottom: 40px;
}

/* Customer & Order Info Grid */
.order-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.order-grid div {
    background: #f7f7f8;
    padding: 20px;
    border-radius: 10px;
}
.order-grid div p {
    margin: 8px 0;
}

/* Products Table */
.order-products table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}
.order-products table thead {
    background: #111;
    color: #fff;
}
.order-products th, .order-products td {
    padding: 15px 20px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.order-products tbody tr:hover {
    background-color: #f5f5f5;
}

/* Status */
.status-select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background: #fff;
    font-size: 14px;
    margin-right: 10px;
}
button.update-btn {
    padding: 8px 18px;
    background-color: #111;
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
}
button.update-btn:hover {
    background-color: #333;
}
</style>
</head>
<body>

<?php include __DIR__.'/src/includes/header.php'; ?>

<main class="order-details-container">
    <h1>Order #<?= $order['order_id'] ?></h1>

    <section>
        <h2>Customer & Order Information</h2>
        <div class="order-grid">
            <div>
                <h3>Customer Info</h3>
                <p><strong>Name:</strong> <?= $order['customer_name'] ?></p>
                <p><strong>Email:</strong> <?= $order['email'] ?></p>
                <p><strong>Phone:</strong> <?= $order['phone'] ?></p>
                <p><strong>Address:</strong> <?= $order['address'] ?>, <?= $order['city'] ?>, <?= $order['postcode'] ?>, <?= $order['country'] ?></p>
            </div>
            <div>
                <h3>Order Info</h3>
                <p><strong>Date:</strong> <?= formatDate($order['order_date']) ?></p>
                <p>
                    <strong>Status:</strong> 
                    <select id="statusSelect" class="status-select">
                        <?php
                        $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered'];
                        foreach ($statuses as $status) {
                            $selected = $status === $order['status'] ? 'selected' : '';
                            echo "<option value='$status' $selected>$status</option>";
                        }
                        ?>
                    </select>
                    <button class="update-btn" id="updateStatusBtn">Update</button>
                </p>
                <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
            </div>
        </div>
    </section>

    <section class="order-products">
        <h2>Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($order_items) === 0): ?>
                    <tr><td colspan="5">No products found for this order.</td></tr>
                <?php else: ?>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['category']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include __DIR__.'/src/includes/footer.php'; ?>

<script>
document.getElementById('updateStatusBtn').addEventListener('click', () => {
    const status = document.getElementById('statusSelect').value;
    const orderId = <?= $order['order_id'] ?>;

    fetch('/deskly/backend/api/updateOrderStatus.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({order_id: orderId, status: status})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert('Order status updated successfully!');
        } else {
            alert('Failed to update order status.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error updating order status.');
    });
});
</script>

</body>
</html>
