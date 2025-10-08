document.addEventListener("DOMContentLoaded", () => {
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

    document.querySelectorAll(".faq-item").forEach(item => {
        item.addEventListener("click", () => {
            item.classList.toggle("active");
        });
    });

    const minusBtn = document.querySelector(".minus");
    const plusBtn = document.querySelector(".plus");
    const inputBox = document.querySelector(".input-box");

    function updateValue(newValue) {
        if (newValue >= 1 && newValue < 11) {
            if (inputBox) inputBox.value = newValue;
        }
    }

    if (minusBtn && plusBtn && inputBox) {
        minusBtn.addEventListener("click", () => {
            updateValue(parseInt(inputBox.value) - 1);
        });

        plusBtn.addEventListener("click", () => {
            updateValue(parseInt(inputBox.value) + 1);
        });

        inputBox.addEventListener("change", () => {
            let value = parseInt(inputBox.value);
            if (isNaN(value)) value = 1;
            updateValue(value);
        });
    }


    const activeImage = document.getElementById("active-image");
    const thumbs = document.querySelectorAll("#image-swiper .thumb");

    if (activeImage && thumbs.length > 0) {
        thumbs.forEach(thumb => {
            thumb.addEventListener("click", () => {
                const img = thumb.querySelector("img");
                if (!img) return;
                const newSrc = img.src;

                activeImage.style.opacity = 0;

                setTimeout(() => {
                    activeImage.src = newSrc;
                    activeImage.style.opacity = 1;
                }, 300);

                thumbs.forEach(t => t.classList.remove("active"));
                thumb.classList.add("active");
            });
        });
    }


    const addBtn = document.getElementById("add-cart-btn");
    if (addBtn) {
        addBtn.addEventListener("click", () => {
            const id = parseInt(addBtn.dataset.id);
            const name = addBtn.dataset.name;
            const price = parseFloat(addBtn.dataset.price);
            const quantityInput = document.querySelector(".input-box");
            const quantity = parseInt(quantityInput.value) || 1;

            addToCart(id, name, price, quantity);
        });
    }

    displayCartItems();
});


function addToCart(id, name, price, quantity) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ id, name, price, quantity });
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    showAddedMessage(name);
    updateCartCount();
}


function showAddedMessage(name) {
    alert(`${name} has been added to your cart!`);
}


function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
    const cartCount = document.getElementById("cart-count");
    if (cartCount) cartCount.textContent = totalItems;
}

let cart = JSON.parse(localStorage.getItem("cart")) || [];

function displayCartItems() {
    const cartItemsContainer = document.getElementById("cartItems");
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = ""; 

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = "<p>Your cart is empty.</p>";
    }

    cart.forEach(item => {
        const productDiv = document.createElement("div");
        productDiv.classList.add("cart-item");

        productDiv.innerHTML = `
            <div class="item-info">
                <span class="item-name">${item.name}</span>
                <span class="item-price">$${item.price}</span>
                <span class="item-quantity">Qty: ${item.quantity}</span>
            </div>
            <button class="remove-btn" onclick="removeFromCart(${item.id})">Remove</button>
        `;

        cartItemsContainer.appendChild(productDiv);
    });

    updateCartSummary();
}


function updateCartSummary() {
    let subtotal = cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
    let tax = subtotal * 0.1;
    let total = subtotal + tax;

    const subtotalEl = document.getElementById("subtotal");
    const taxEl = document.getElementById("tax");
    const totalEl = document.getElementById("total");

    if (subtotalEl) subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    if (taxEl) taxEl.textContent = `$${tax.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `$${total.toFixed(2)}`;
}


function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem("cart", JSON.stringify(cart));
    displayCartItems();
}

function proceedToCheckout() {
    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }
    alert("Proceeding to checkout...");

}


document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector(".search-input");
  const products = document.querySelectorAll(".product-card");
  const grid = document.getElementById("productsGrid");

  if (!searchInput) return;

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
});

