<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Deskly</title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=1">
    <style>
        .checkout-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
        }

        .checkout-form section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .checkout-form h2 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.95rem;
        }
        
        .checkout-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .checkout-summary h3 {
            margin-bottom: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .summary-total {
            border-top: 1px solid #ddd;
            padding-top: 15px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
        }

        .place-order-btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
            transition: background 0.2s;
        }

        .place-order-btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php';?>

    <div class="checkout-container">
        <div class="checkout-form">
            <form id="checkoutForm">
                <section>
                    <h2>Shipping Details</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="firstName" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="lastName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" id="phone" required>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" id="address" placeholder="Street address" required>
                    </div>

                    <div class="form-group">
                        <label>Landmark (Optional)</label>
                        <input type="text" name="landmark" id="landmark">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" id="city" required>
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name="postcode" id="postcode" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country" id="country" required>
                    </div>
                </section>

                <section>
                    <h2>Payment Method</h2>
                    <div class="form-group">
                        <label><input type="radio" name="payment" value="cod" checked> Cash on Delivery</label>
                    </div>
                </section>
            </form>
        </div>

        <div class="checkout-summary">
            <h3>Your Order</h3>
            <div id="orderItemsSummary">
                 <p>Loading summary...</p>
            </div>
            
            <div class="summary-total">
                <span>Total</span>
                <span id="checkoutTotal">$0.00</span>
            </div>

            <button type="submit" form="checkoutForm" class="place-order-btn">Place Order</button>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
             try {
                const userRes = await fetch('/deskly/backend/api/get_user_details.php'); 
             } catch (e) { console.log("User details fetch error", e); }

            loadCheckoutSummary();
            
            document.getElementById('checkoutForm').addEventListener('submit', handlePlaceOrder);
        });

        async function loadCheckoutSummary() {
            try {
                const res = await fetch('/deskly/backend/api/getcart.php');
                const data = await res.json();
                
                if (!data.success || !data.items.length) {
                    alert("Your cart is empty!");
                    window.location.href = 'store.php';
                    return;
                }

                const container = document.getElementById('orderItemsSummary');
                container.innerHTML = '';
                
                let subtotal = 0;
                data.items.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'summary-item';
                    div.innerHTML = `
                        <span>${item.name} x ${item.quantity}</span>
                        <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    `;
                    container.appendChild(div);
                    subtotal += item.price * item.quantity;
                });

                const tax = subtotal * 0.10;
                const total = subtotal + tax;
                
                // Add tax
                 const taxDiv = document.createElement('div');
                 taxDiv.className = 'summary-item';
                 taxDiv.style.color = '#777';
                 taxDiv.innerHTML = `<span>Tax (10%)</span><span>$${tax.toFixed(2)}</span>`;
                 container.appendChild(taxDiv);

                document.getElementById('checkoutTotal').textContent = `$${total.toFixed(2)}`;
            } catch (err) {
                console.error(err);
            }
        }
        
        // Fetch user data
        (async function prefillData() {
             try {
                const res = await fetch('/deskly/backend/api/get_user_details.php');
                if(res.ok) {
                    const data = await res.json();
                    if(data.success) {
                        const u = data.user;
                        if(u.first_name) document.getElementById('firstName').value = u.first_name;
                        if(u.last_name) document.getElementById('lastName').value = u.last_name;
                        if(u.phone) document.getElementById('phone').value = u.phone;
                        if(u.address) document.getElementById('address').value = u.address;
                        if(u.landmark) document.getElementById('landmark').value = u.landmark;
                        if(u.city) document.getElementById('city').value = u.city;
                        if(u.postcode) document.getElementById('postcode').value = u.postcode;
                        if(u.country) document.getElementById('country').value = u.country;
                    }
                }
             } catch(e) {}
        })();

        async function handlePlaceOrder(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/deskly/backend/api/checkout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await res.json();
                
                if (result.success) {
                    alert("Order placed successfully!");
                    window.location.href = 'profile.php#orders-section';
                } else {
                    alert("Failed to place order: " + result.message);
                }
            } catch (err) {
                console.error(err);
                alert("An error occurred.");
            }
        }
    </script>
</body>
</html>
