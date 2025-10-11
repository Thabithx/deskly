document.addEventListener("DOMContentLoaded", () => {

    // ===== Filter / Sort / Price Inputs =====
    document.querySelectorAll('#sort, #category-filter').forEach(el => {
        el.addEventListener('change', () => {
            const filterForm = document.getElementById('filter-form');
            if (filterForm) filterForm.submit();
        });
    });

    document.querySelectorAll('input[name="minPrice"], input[name="maxPrice"]').forEach(el => {
        el.addEventListener('change', () => {
            const filterForm = document.getElementById('filter-form');
            if (filterForm) filterForm.submit();
        });
    });

    // ===== FAQ Toggle =====
    document.querySelectorAll(".faq-item").forEach(item => {
        item.addEventListener("click", () => item.classList.toggle("active"));
    });

    // ===== Product Quantity Buttons (Product Page) =====
    const minusBtn = document.querySelector(".minus");
    const plusBtn = document.querySelector(".plus");
    const inputBox = document.querySelector(".input-box");

    function updateValue(newValue) {
        if (newValue >= 1 && newValue < 11) {
            if (inputBox) inputBox.value = newValue;
        }
    }

    if (minusBtn && plusBtn && inputBox) {
        minusBtn.addEventListener("click", () => updateValue(parseInt(inputBox.value) - 1));
        plusBtn.addEventListener("click", () => updateValue(parseInt(inputBox.value) + 1));
        inputBox.addEventListener("change", () => {
            let value = parseInt(inputBox.value);
            if (isNaN(value)) value = 1;
            updateValue(value);
        });
    }

    // ===== Product Image Gallery =====
    const activeImage = document.getElementById("active-image");
    const thumbs = document.querySelectorAll("#image-swiper .thumb");
    if (activeImage && thumbs.length > 0) {
        thumbs.forEach(thumb => {
            thumb.addEventListener("click", () => {
                const img = thumb.querySelector("img");
                if (!img) return;
                activeImage.style.opacity = 0;
                setTimeout(() => {
                    activeImage.src = img.src;
                    activeImage.style.opacity = 1;
                }, 300);
                thumbs.forEach(t => t.classList.remove("active"));
                thumb.classList.add("active");
            });
        });
    }

    // ===== Search =====
    const searchInput = document.querySelector(".search-input");
    const products = document.querySelectorAll(".product-card");
    const grid = document.getElementById("productsGrid");
    if (searchInput && grid) {
        const message = document.createElement("p");
        message.style.textAlign = "center";
        message.style.display = "none";
        grid.parentNode.insertBefore(message, grid.nextSibling);

        searchInput.addEventListener("input", () => {
            const query = searchInput.value.toLowerCase().trim();
            message.textContent = "Searching...";
            message.style.display = "block";

            setTimeout(() => {
                let visibleCount = 0;
                products.forEach(product => {
                    const name = product.querySelector("h1").textContent.toLowerCase();
                    if (name.includes(query) || query === "") {
                        product.style.display = "";
                        visibleCount++;
                    } else {
                        product.style.display = "none";
                    }
                });

                if (visibleCount === 0) {
                    message.textContent = "No products found.";
                } else {
                    message.style.display = "none";
                }
            }, 150);
        });
    }

    // ===== Logout =====
    const logoutBtn = document.querySelector(".logout-btn");
    if (logoutBtn) {
        logoutBtn.addEventListener("click", e => {
            e.preventDefault();
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = logoutBtn.getAttribute("href");
            }
        });
    }

    // ===== Add to Cart (Product Page) =====
    const addCartBtn = document.getElementById("add-cart-btn");
    if (addCartBtn) {
        addCartBtn.addEventListener("click", async () => {
            const productId = addCartBtn.dataset.id;
            const quantity = parseInt(document.querySelector(".input-box").value) || 1;

            try {
                const res = await fetch("/deskly/backend/api/addcart.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ product_id: productId, quantity })
                });
                const result = await res.json();
                if (result.redirect) return window.location.href = result.redirect;
                alert(result.success ? "Product added to cart!" : result.message || "Error adding product.");
                loadCart(); // refresh cart
            } catch (err) {
                console.error(err);
                alert("Error adding product. Try again.");
            }
        });
    }

    // ===== Load Cart on Page Load =====
    loadCart();

    // ===== CART BUTTON EVENT DELEGATION (outside loadCart) =====
    const cartContainer = document.getElementById("cartItems");
    if (cartContainer) {

        // Handle + / - / remove buttons
        cartContainer.addEventListener("click", async e => {
            const target = e.target;
            const productId = target.dataset.id;
            if (!productId) return;

            if (target.classList.contains("remove-btn")) {
                await removeFromCart(productId);
                loadCart();
            }

            if (target.classList.contains("cart-plus") || target.classList.contains("cart-minus")) {
                const input = cartContainer.querySelector(`.qty-input[data-id='${productId}']`);
                let quantity = parseInt(input.value);
                if (target.classList.contains("cart-plus")) quantity++;
                if (target.classList.contains("cart-minus")) quantity = Math.max(1, quantity - 1);
                input.value = quantity;
                await updateCartQuantity(productId, quantity);
                loadCart();
            }
        });

        // Handle direct input changes
        cartContainer.addEventListener("input", async e => {
            if (!e.target.classList.contains("qty-input")) return;
            const productId = e.target.dataset.id;
            let quantity = parseInt(e.target.value);
            if (isNaN(quantity) || quantity < 1) quantity = 1;
            e.target.value = quantity;
            await updateCartQuantity(productId, quantity);
            loadCart();
        });
    }

}); // end DOMContentLoaded

// ===== CART FUNCTIONS =====
async function loadCart() {
    try {
        const cartContainer = document.getElementById("cartItems");
        const subtotalEl = document.getElementById("subtotal");
        const taxEl = document.getElementById("tax");
        const totalEl = document.getElementById("total");

        if (!cartContainer) return;

        const res = await fetch("/deskly/backend/api/getcart.php");
        const result = await res.json();

        if (result.redirect) return window.location.href = result.redirect;

        if (!result.success || !result.items.length) {
            cartContainer.innerHTML = "<tr><td colspan='6' style='text-align:center;'>Your cart is empty.</td></tr>";
            subtotalEl.textContent = taxEl.textContent = totalEl.textContent = "$0.00";
            return;
        }

        let subtotal = 0;
        cartContainer.innerHTML = "";

        result.items.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td><img src="http://localhost${item.image}" width="50" alt="${item.name}"></td>
                <td>${item.name}</td>
                <td>$${item.price}</td>
                <td>
                  <div style="display:flex;">
                    <button class="qty-btn cart-minus" data-id="${item.product_id}">-</button>
                    <input type="number" class="qty-input" data-id="${item.product_id}" value="${item.quantity}" min="1" style="width:50px;text-align:center;">
                    <button class="qty-btn cart-plus" data-id="${item.product_id}">+</button>
                  </div>
                </td>
                <td>$${(item.price * item.quantity).toFixed(2)}</td>
                <td><button class="remove-btn" data-id="${item.product_id}">Remove</button></td>
            `;
            cartContainer.appendChild(row);
            subtotal += item.price * item.quantity;
        });

        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
        taxEl.textContent = `$${tax.toFixed(2)}`;
        totalEl.textContent = `$${total.toFixed(2)}`;

    } catch (err) {
        console.error("Error loading cart:", err);
    }
}

async function removeFromCart(productId) {
    try {
        const res = await fetch("/deskly/backend/api/removecart.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: productId })
        });
        const result = await res.json();
        if (result.redirect) window.location.href = result.redirect;
    } catch (err) {
        console.error("Error removing item:", err);
    }
}

async function updateCartQuantity(productId, quantity) {
    try {
        const res = await fetch("/deskly/backend/api/updatecart.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: productId, quantity })
        });
        const result = await res.json();
        if (result.redirect) window.location.href = result.redirect;
    } catch (err) {
        console.error("Error updating quantity:", err);
    }
}
