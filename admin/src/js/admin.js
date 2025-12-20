document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const tableRows = document.querySelectorAll("#ordersBody tr");

    //search filter
    searchInput.addEventListener("keyup", () => {
        const searchValue = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? "" : "none";
        });
    });

    // Active button styling
    document.querySelectorAll(".filter-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.querySelectorAll(".filter-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
        });
    });
    const imagesInput = document.getElementById('images');
        const previewContainer = document.getElementById('preview-images');

        imagesInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-img');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });

});
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");
    const tableRows = document.querySelectorAll("#ordersBody tr");

    searchInput.addEventListener("keyup", () => {
        const searchValue = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(searchValue) ? "" : "none";
        });
    });

    document.querySelectorAll("tr.clickable").forEach(row => {
        row.addEventListener("click", () => {
            const orderId = row.getAttribute("data-order-id");
            window.location.href = `/deskly/admin/orderDetail.php?order_id=${orderId}`;
        });
    });

    const entriesSelect = document.getElementById("entries");
    const tbody = document.getElementById("ordersBody");
    let currentPage = 1;
    let rowsPerPage = parseInt(entriesSelect.value);

    function paginate() {
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        const paginationDiv = document.getElementById("pagination");
        paginationDiv.innerHTML = "";

        rows.forEach((row, index) => {
            row.style.display = (index >= (currentPage-1)*rowsPerPage && index < currentPage*rowsPerPage) ? "" : "none";
        });

        //pagination buttons
        for(let i=1; i<=totalPages; i++){
            const btn = document.createElement("button");
            btn.textContent = i;
            if(i === currentPage) btn.classList.add("active");
            btn.addEventListener("click", () => { currentPage = i; paginate(); });
            paginationDiv.appendChild(btn);
        }
    }

    entriesSelect.addEventListener("change", () => {
        rowsPerPage = parseInt(entriesSelect.value);
        currentPage = 1;
        paginate();
    });

    paginate();
});
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".remove-btn").forEach(button => {
    button.addEventListener("click", async () => {
      const userId = button.dataset.id;
      const row = document.getElementById(`user-${userId}`);

      if (!confirm("Are you sure you want to delete this user?")) return;

      try {
        const response = await fetch("/deskly/backend/api/deleteUser.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id=${encodeURIComponent(userId)}`
        });

        const text = await response.text();
        console.log("Server response:", text);

        let data;
        try {
          data = JSON.parse(text);
        } catch {
          alert("Invalid JSON response from server:\n" + text);
          return;
        }

        if (data.success) {
          // Smooth fade-out animation
          row.style.transition = "opacity 0.4s ease, transform 0.3s ease";
          row.style.opacity = "0";
          row.style.transform = "translateX(-10px)";
          setTimeout(() => row.remove(), 400);
        } else {
          alert("Error deleting user: " + (data.error || "Unknown error"));
        }
      } catch (err) {
        console.error("Delete error:", err);
        alert("Something went wrong while deleting the user.");
      }
    });
  });
});
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            const productId = new URL(button.href).searchParams.get("id");
            const card = button.closest(".product-card");

            if (!confirm("Are you sure you want to delete this product?")) return;

            fetch("/deskly/backend/api/deleteProduct.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${encodeURIComponent(productId)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Fade out product card
                    card.style.transition = "opacity 0.4s ease, transform 0.3s ease";
                    card.style.opacity = "0";
                    card.style.transform = "scale(0.95)";
                    setTimeout(() => card.remove(), 400);
                } else {
                    alert("Error deleting product: " + (data.error || "Unknown error"));
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Something went wrong while deleting the product.");
            });
        });
    });
});
const imagesInput = document.getElementById('images');
const previewContainer = document.getElementById('preview-images');
imagesInput.addEventListener('change', function() {
  previewContainer.innerHTML = '';
  Array.from(this.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.classList.add('preview-img');
      previewContainer.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});

// Handle deletion of existing images
const existingContainer = document.getElementById('existing-images');
const remainingInput = document.getElementById('remaining_images');

existingContainer.addEventListener('click', e => {
  const imgDiv = e.target.closest('.preview-img');
  if (!imgDiv) return;

  const confirmed = confirm('Remove this image?');
  if (confirmed) {
    imgDiv.remove();
    const updated = Array.from(existingContainer.querySelectorAll('.preview-img')).map(div => div.dataset.src);
    remainingInput.value = JSON.stringify(updated);
  }
});
async function fetchMessages() {
    const tbody = document.querySelector('.messages-table tbody');
    tbody.innerHTML = '';

    const res = await fetch('/deskly/backend/api/getMessages.php');
    const messages = await res.json();

    messages.forEach(msg => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${msg.id}</td>
            <td>${msg.name}</td>
            <td>${msg.email}</td>
            <td>${msg.message}</td>
            <td>${msg.created_at.split(' ')[0]}</td>
            <td>
                ${msg.status === 'Pending' ? `<button class="action-btn reply" data-id="${msg.id}">Reply</button>` : `<span>Answered</span>`}
                <button class="action-btn delete" data-id="${msg.id}">Delete</button>
            </td>
        `;

        tbody.appendChild(tr);
    });

    // Add reply button listeners
    document.querySelectorAll('.action-btn.reply').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const answer = prompt('Enter your reply:');
            if (!answer) return;

            const formData = new FormData();
            formData.append('id', id);
            formData.append('answer', answer);

            const res = await fetch('/deskly/backend/api/replyMessage.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                alert('Reply sent successfully');
                fetchMessages();
            } else {
                alert((data.error || 'Failed to reply'));
            }
        });
    });

    //delete button listeners
    document.querySelectorAll('.action-btn.delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            if (!confirm('Are you sure you want to delete this message?')) return;

            const res = await fetch(`/deskly/backend/api/deleteMessage.php?id=${id}`);
            const data = await res.json();

            if (data.success) {
                alert('Message deleted');
                fetchMessages();
            } else {
                alert((data.error || 'Failed to delete'));
            }
        });
    });
}

fetchMessages();
