// DOM elements
const productList = document.getElementById('product-list');
const pagination = document.getElementById('pagination');
const searchForm = document.getElementById('search-form');

// Fetch products from server
function fetchProducts(page = 1, search = '') {
    fetch(`dashboard.php?page=${page}&search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            renderProducts(data.products);
            renderPagination(data.total_pages, data.current_page, search);
        })
        .catch(error => console.error('Error fetching products:', error));
}

// Render products to the list
function renderProducts(products) {
    productList.innerHTML = ''; // Clear existing products
    if (products.length === 0) {
        productList.innerHTML = '<p>No products found.</p>';
        return;
    }

    products.forEach(product => {
        const productItem = document.createElement('div');
        productItem.className = 'product-item';
        productItem.innerHTML = `
            <img src="${product.image_path}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>${product.description}</p>
            <p>Price: $${product.price}</p>
            <p>Quantity: ${product.quantity}</p>
        `;
        productList.appendChild(productItem);
    });
}

// Render pagination controls
function renderPagination(totalPages, currentPage, search) {
    pagination.innerHTML = ''; // Clear existing pagination
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.textContent = i;
        pageButton.className = i === currentPage ? 'active' : '';
        pageButton.addEventListener('click', () => fetchProducts(i, search));
        pagination.appendChild(pageButton);
    }
}

// Search form submission
searchForm.addEventListener('submit', event => {
    event.preventDefault();
    const searchInput = document.getElementById('search').value;
    fetchProducts(1, searchInput);
});

// Initial fetch
fetchProducts();
