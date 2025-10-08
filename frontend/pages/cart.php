<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php';?>



    <div class="cart">
        <section class="cart-section">
            <div class="cart-container">
                <div class="cart-items" id="cartItems">
                </div>
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-line">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="summary-line">
                        <span>Tax:</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="summary-line total">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                    <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
                </div>
            </div>
        </section>
    </div>


    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>
