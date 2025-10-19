<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Order | Deskly</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css?v=1">
  <style>

    .container {
      max-width: 900px;
      margin: 60px auto;
      background: #fff;
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }
  </style>
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <h1>Track Your Order</h1>
  <div id="orderDetails">Loading order details...</div>

  <div class="timeline" id="orderTimeline">
    <div class="timeline-progress" id="timelineProgress"></div>

    <div class="step" id="step1">
      <div class="circle">🛒</div>
      <div class="label">Order Placed</div>
    </div>
    <div class="step" id="step2">
      <div class="circle">⚙️</div>
      <div class="label">Processing</div>
    </div>
    <div class="step" id="step3">
      <div class="circle">🚚</div>
      <div class="label">Shipped</div>
    </div>
    <div class="step" id="step4">
      <div class="circle">📦</div>
      <div class="label">Delivered</div>
    </div>
  </div>
  <div style="display:flex; justify-content:center; margin-top:50px"><a href="http://localhost/deskly/frontend/pages/profile.php#orders-section"><button>Orders</button></a></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const orderId = new URLSearchParams(window.location.search).get("order_id");
  const orderDetails = document.getElementById("orderDetails");
  const timelineProgress = document.getElementById("timelineProgress");

  // Helper to format datetime nicely
  function formatDateTime(timestamp) {
    const options = {
      weekday: "short",
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
      hour12: true
    };
    return new Date(timestamp).toLocaleString("en-US", options);
  }

  if (!orderId) {
    orderDetails.innerHTML = "<p class='not-found'>Invalid order ID.</p>";
    return;
  }

  fetch("/deskly/backend/api/getOrderTracking.php?order_id=" + orderId)
    .then(res => res.json())
    .then(data => {
      if (data.success && data.order) {
        const order = data.order;
        const items = data.items;

        orderDetails.innerHTML = `
          <div class="info-card">
            <p><strong>Order ID:</strong> #${order.order_id}</p>
            <p><strong>Customer:</strong> ${order.customer_name}</p>
            <p><strong>Email:</strong> ${order.email}</p>
            <p><strong>Total Amount:</strong> Rs. ${order.total_amount}</p>
            <p><strong>Status:</strong> ${order.status}</p>
            <p><strong>Order Date:</strong> ${formatDateTime(order.order_date)}</p>
            <p><strong>Estimated Delivery:</strong> ${formatDateTime(order.estimated_delivery)}</p>
          </div>
        `;

        if (items.length > 0) {
          let itemsHTML = `<div class="items-card"><h3>Items</h3>`;
          items.forEach(item => {
            itemsHTML += `
              <div class="item">
                <div class="item-details">
                  <span>${item.name} x${item.quantity}</span>
                </div>
                <span>Rs. ${item.price}</span>
              </div>
            `;
          });
          itemsHTML += `</div>`;
          orderDetails.innerHTML += itemsHTML;
        }

        // Timeline
        const steps = ["pending","processing","shipped","delivered"];
        let activeIndex = steps.indexOf(order.status.toLowerCase());
        if(activeIndex === -1) activeIndex = 0;

        for(let i=0;i<=activeIndex;i++){
          document.getElementById(`step${i+1}`).classList.add("active");
        }

        // Animate progress bar
        timelineProgress.style.width = `${(activeIndex)/(steps.length-1)*100}%`;

      } else {
        orderDetails.innerHTML = "<p class='not-found'>Order not found.</p>";
      }
    })
    .catch(err => {
      console.error(err);
      orderDetails.innerHTML = "<p class='not-found'>Something went wrong while loading your order.</p>";
    });
});
</script>

</body>
</html>
