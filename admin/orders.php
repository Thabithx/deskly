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
            <div class="orders-filters">
                <select class="filter-dropdown">
                    <option>Week</option>
                    <option>Month</option>
                    <option>Year</option>
                </select>
            </div>
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
                        <th>Category</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    <tr>
                        <td>#87451</td>
                        <td>Esther Howard</td>
                        <td>02/03/2022</td>
                        <td>$200</td>
                        <td>Notebook</td>
                        <td class="status delivered">Delivered</td>
                    </tr>
                    <tr>
                        <td>#87452</td>
                        <td>Wade Warren</td>
                        <td>02/03/2022</td>
                        <td>$220</td>
                        <td>Notebook</td>
                        <td class="status delivered">Delivered</td>
                    </tr>
                    <tr>
                        <td>#87453</td>
                        <td>Jenny Wilson</td>
                        <td>02/03/2022</td>
                        <td>$300</td>
                        <td>Notebook</td>
                        <td class="status delivered">Delivered</td>
                    </tr>
                    <tr>
                        <td>#87454</td>
                        <td>Guy Hawkins</td>
                        <td>02/03/2022</td>
                        <td>$400</td>
                        <td>Notebook</td>
                        <td class="status delivered">Delivered</td>
                    </tr>
                    <tr>
                        <td>#87455</td>
                        <td>Robert Fox</td>
                        <td>02/03/2022</td>
                        <td>$450</td>
                        <td>Notebook</td>
                        <td class="status delivered">Delivered</td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination">
                <button disabled>&laquo;</button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>&raquo;</button>
            </div>
        </section>
    </main>

    <?php include __DIR__.'/src/includes/footer.php'?>
    <script src="/deskly/admin/src/js/orders.js"></script>
</body>
</html>
