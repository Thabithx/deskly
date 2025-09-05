// Product fetching and rendering utilities
async function fetchProducts({ limit = 8, featured = false } = {}) {
  const params = new URLSearchParams();
  if (limit) params.append('limit', String(limit));
  if (featured) params.append('featured', '1');
  const url = `/backend/api/products.php?${params.toString()}`;
  const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
  if (!response.ok) throw new Error('Failed to fetch products');
  const data = await response.json();
  return Array.isArray(data) ? data : (data.products || []);
}

function createProductCardElement(product) {
  const card = document.createElement('div');
  card.className = 'product-card';
  card.setAttribute('data-id', product.id);

  const productUrl = `frontend/pages/product.php?id=${encodeURIComponent(product.id)}`;

  card.innerHTML = `
    <a href="${productUrl}" class="product-image-link">
      <img src="${product.image || 'frontend/assets/images/deskly_logo.png'}" alt="${product.name}" class="product-image" />
    </a>
    <div class="product-info">
      <a href="${productUrl}" class="product-name">${product.name}</a>
      <div class="product-meta">
        <span class="product-price">${product.currency || '$'}${Number(product.price || 0).toFixed(2)}</span>
        <button class="add-to-cart" data-id="${product.id}">Add</button>
      </div>
    </div>
  `;
  return card;
}

async function renderProductsInto(containerSelector, { limit = 8, featured = false } = {}) {
  const container = document.querySelector(containerSelector);
  if (!container) return;
  container.classList.add('product-grid');
  try {
    const products = await fetchProducts({ limit, featured });
    container.innerHTML = '';
    products.forEach((p) => container.appendChild(createProductCardElement(p)));
  } catch (e) {
    container.innerHTML = '<p style="color:#888">Failed to load products.</p>';
  }
}

// Auto-render featured on homepage if placeholder exists
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('#featured-products')) {
    renderProductsInto('#featured-products', { limit: 4, featured: true });
  }
});


