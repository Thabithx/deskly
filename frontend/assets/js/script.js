document.querySelectorAll('#sort, #category-filter').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });

document.querySelectorAll('input[name="minPrice"], input[name="maxPrice"]').forEach(el => {
        el.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });

document.querySelectorAll(".faq-item").forEach(item => {
      item.addEventListener("click", () => {
        item.classList.toggle("active");
      });
    });