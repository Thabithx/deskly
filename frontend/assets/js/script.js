const featuredDiv = document.getElementById("featured-div");
const dots = document.querySelectorAll("#featured-dots .dot");

function goToSlide(index) {
  const cardWidth = featuredDiv.querySelector(".featured-div-products").offsetWidth + 20; // +gap
  featuredDiv.scrollTo({
    left: cardWidth * index,
    behavior: "smooth"
  });
  setActiveDot(index);
}

function setActiveDot(index) {
  dots.forEach((dot, i) => {
    dot.classList.toggle("active", i === index);
  });
}

// set first dot active
if (dots.length > 0) setActiveDot(0);
