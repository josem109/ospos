let inventory = [];
let currentDisplayedProducts = [];
let currentPage = 1;
const itemsPerPage = 25;

const searchInput = document.getElementById("searchInput");
const searchButton = document.getElementById("searchButton");
const productTableBody = document.getElementById("productTableBody");
const dolarParaleloInput = document.getElementById("dolarParalelo");
const dolarBCVInput = document.getElementById("dolarBCV");
const paginationContainer = document.getElementById("paginationContainer");

// Load the inventory data from the JSON file
fetch("inventory.json")
  .then((response) => response.json())
  .then((data) => {
    inventory = data.products;
    currentDisplayedProducts = inventory;
    displayProducts();
    setupPagination();
  })
  .catch((error) => console.error("Error loading inventory:", error));

searchButton.addEventListener("click", searchProducts);
searchInput.addEventListener("keyup", function (event) {
  if (event.key === "Enter") {
    searchProducts();
  }
});

dolarParaleloInput.addEventListener("input", updatePrices);
dolarBCVInput.addEventListener("input", updatePrices);

function searchProducts() {
  const query = searchInput.value.toLowerCase();
  currentDisplayedProducts = inventory.filter((item) => {
    if (!item || typeof item !== "object") return false;

    const codebarMatch =
      item.codebar &&
      typeof item.codebar === "string" &&
      item.codebar.toLowerCase().includes(query);
    const nameMatch =
      item.name &&
      typeof item.name === "string" &&
      item.name.toLowerCase().includes(query);

    return codebarMatch || nameMatch;
  });
  currentPage = 1;
  displayProducts();
  setupPagination();
}

function createProductRow(product) {
  const row = document.createElement("tr");
  const dolarParalelo = parseFloat(dolarParaleloInput.value) || 0;
  const dolarBCV = parseFloat(dolarBCVInput.value) || 1;
  const price = parseFloat(product.price) || 0;
  const priceBs = price * dolarParalelo;
  const finalPrice = dolarBCV !== 0 ? priceBs / dolarBCV : 0;

  row.innerHTML = `
        <td>${product.codebar || "N/A"}</td>
        <td>${product.name || "N/A"}</td>
        <td>${product.stock || 0}</td>
        <td>$${price.toFixed(2)}</td>
        <td>${priceBs.toFixed(2)}</td>
        <td>$${finalPrice.toFixed(2)}</td>
    `;
  return row;
}

function displayProducts() {
  productTableBody.innerHTML = ""; // Clear existing rows
  const startIndex = (currentPage - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  const paginatedProducts = currentDisplayedProducts.slice(
    startIndex,
    endIndex
  );

  if (paginatedProducts.length === 0) {
    const noResultRow = document.createElement("tr");
    noResultRow.innerHTML =
      '<td colspan="6" class="text-center">No products found</td>';
    productTableBody.appendChild(noResultRow);
  } else {
    paginatedProducts.forEach((product) => {
      const productRow = createProductRow(product);
      productTableBody.appendChild(productRow);
    });
  }
}

function updatePrices() {
  displayProducts();
}

function setupPagination() {
  const pageCount = Math.ceil(currentDisplayedProducts.length / itemsPerPage);
  paginationContainer.innerHTML = "";

  // First page
  addPaginationButton("First", 1, currentPage > 1);

  // Previous page
  addPaginationButton("Previous", currentPage - 1, currentPage > 1);

  // Page numbers
  const startPage = Math.max(1, currentPage - 2);
  const endPage = Math.min(pageCount, currentPage + 2);

  for (let i = startPage; i <= endPage; i++) {
    addPaginationButton(i.toString(), i, true, i === currentPage);
  }

  // Next page
  addPaginationButton("Next", currentPage + 1, currentPage < pageCount);

  // Last page
  addPaginationButton("Last", pageCount, currentPage < pageCount);
}

function addPaginationButton(text, pageNumber, enabled, isActive = false) {
  const li = document.createElement("li");
  li.className = `page-item ${!enabled ? "disabled" : ""} ${
    isActive ? "active" : ""
  }`;

  const a = document.createElement("a");
  a.className = "page-link";
  a.href = "#";
  a.textContent = text;

  if (enabled) {
    a.addEventListener("click", (e) => {
      e.preventDefault();
      currentPage = pageNumber;
      displayProducts();
      setupPagination();
    });
  }

  li.appendChild(a);
  paginationContainer.appendChild(li);
}

// Initial display
displayProducts();
setupPagination();
