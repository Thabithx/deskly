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

    const minusBtn = document.querySelector(".minus");
    const plusBtn = document.querySelector(".plus");
    const inputBox = document.querySelector(".input-box");

    function updateValue(newValue) {
        if (newValue>=1 && newValue<11){
            inputBox.value = newValue;
        }
    }

    minusBtn.addEventListener("click", () => {
        updateValue(parseInt(inputBox.value) - 1);
    });

    plusBtn.addEventListener("click", () => {
        updateValue(parseInt(inputBox.value) + 1);
    });

    inputBox.addEventListener("change", () => {
        let value = parseInt(inputBox.value);
        if (isNaN(value)) value = min;
        updateValue(value);
    });

    document.addEventListener("DOMContentLoaded", () => {
    const activeImage = document.getElementById("active-image");
    const thumbs = document.querySelectorAll("#image-swiper .thumb");

    thumbs.forEach(thumb => {
        thumb.addEventListener("click", () => {
            // Update main image
            const newSrc = thumb.querySelector("img").src;
            activeImage.style.opacity = 0;

            setTimeout(() => {
                activeImage.src = newSrc;
                activeImage.style.opacity = 1;
            }, 300);

            // Update active thumbnail
            thumbs.forEach(t => t.classList.remove("active"));
            thumb.classList.add("active");
        });
    });
});

    
