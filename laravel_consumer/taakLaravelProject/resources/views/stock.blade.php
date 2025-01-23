<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraad</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div id="message" ></div>
        <div class="d-flex justify-content-start mb-4">
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/projects'">Projecten</button>
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/stock'">Voorraad</button>
            <button class="btn btn-secondary me-3" onclick="window.location.href = '/monitoring'">Werf Monitoring</button>
        </div>
        <h1 class="text-center mb-4">Voorraad Beheren</h1>
        <div class="d-flex justify-content-between mb-4">
            <div>
                <button class="btn btn-primary me-2" onclick="fetchProducts()">Producten</button>
                <button class="btn btn-primary me-2" onclick="fetchSuppliers()">Leveranciers</button>
                <button class="btn btn-primary" onclick="fetchRacks()">Rekken</button>
            </div>
            <div>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#newProductModal">
                    Nieuw Product
                </button>
                <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#newSupplierModal">
                    Nieuwe Leverancier
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newRackModal">
                    Nieuw Rek
                </button>
            </div>
        </div>

        <table class="table table-bordered d-none" id="ProductsTable">
            <thead>
                <tr>
                    <th>Product Naam</th>
                    <th>Beschrijving</th>
                    <th>Eenheidsprijs</th>
                    <th>Hoeveelheid</th>
                    <th>Leverancier</th>
                    <th>Locatie</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="ProductsTableBody">
            <!-- Rows will be dynamically added here -->
            </tbody>
        </table>

        <table class="table table-bordered d-none" id="SuppliersTable">
            <thead>
                <tr>
                    <th>Leverancier Naam</th>
                    <th>Email</th>
                    <th>Adres</th>
                    <th>Producten</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="SuppliersTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>

        <table class="table table-bordered d-none" id="RacksTable">
            <thead>
                <tr>
                    <th>Rek Naam</th>
                    <th>Aantal rijen</th>
                    <th>Producten</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="RacksTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <!-- Modal voor het aanmaken van een nieuw project -->
    <div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProductLabel">Nieuw Product Aanmaken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newProductForm" onsubmit="createProduct(event)">
                        <div class="mb-3">
                            <label for="newProductName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="newProductName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProductDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="newProductDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="newProductPrice" class="form-label">Prijs</label>
                            <input type="text" class="form-control" id="newProductPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProductQuantity" class="form-label">Hoeveelheid</label>
                            <input type="number" class="form-control" id="newProductQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProductSupplier" class="form-label">Leverancier</label>
                            <select id="newProductSupplier" class="form-select" required>
                                <option value="">Kies een leverancier</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newProductRack" class="form-label">Rek</label>
                            <select id="newProductRack" class="form-select" required>
                                <option value="">Kies een rek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="newProductRow" class="form-label">Mogelijke rijen</label>
                            <select id="newProductRow" class="form-select" required>
                                <option value="">Kies een rij</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newSupplierModal" tabindex="-1" aria-labelledby="newSupplierLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newSupplierLabel">Nieuwe Leverancier Aanmaken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-3">
                    <form id="newSupplierForm" onsubmit="createSupplier(event)">
                        <div class="mb-3">
                            <label for="newSupplierName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="newSupplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newSupplierEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="newSupplierEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="newSupplierAddress" class="form-label">Adres</label>
                            <input type="text" class="form-control" id="newSupplierAddress" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newRackModal" tabindex="-1" aria-labelledby="newRackLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRackLabel">Nieuw Rek Aanmaken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-3">
                    <form id="newRackForm" onsubmit="createRack(event)">
                        <div class="mb-3">
                            <label for="newRackName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="newRackName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newRackRows" class="form-label">Aantal rijen</label>
                            <input type="number" class="form-control" id="newRackRows" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductLabel">Product Bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" onsubmit="updateProduct(event)">
                        <input type="hidden" id="editProductId">
                        <input type="hidden" id="editSupplierId">
                        <input type="hidden" id="editLocationId">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editProductName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="editProductDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Prijs</label>
                            <input type="text" class="form-control" id="editProductPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductQuantity" class="form-label">Hoeveelheid</label>
                            <input type="number" class="form-control" id="editProductQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductSupplier" class="form-label">Leverancier</label>
                            <input type="text" class="form-control" id="editProductSupplier" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="currentProductLocation" class="form-label">Huidige locatie</label>
                            <input type="text" class="form-control" id="currentProductLocation" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editProductLocationRadio" class="form-label">Verander rek</label>
                            <select id="editProductLocationRadio" class="form-select" required>
                                <option value="0">Nee</option>
                                <option value="1">Ja</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProductRack" class="form-label">Rek</label>
                            <select id="editProductRack" class="form-select">
                                <option value="">Kies een rek</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProductRow" class="form-label">Rij</label>
                            <select id="editProductRow" class="form-select">
                                <option value="">Kies een rij</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Bewerken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierLabel">Leverancier Bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-3">
                    <form id="editSupplierForm" onsubmit="updateSupplier(event)">
                        <input type="hidden" id="editSupplierId">
                        <div class="mb-3">
                            <label for="editSupplierName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editSupplierName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSupplierEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editSupplierEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSupplierAddress" class="form-label">Adres</label>
                            <input type="text" class="form-control" id="editSupplierAddress" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bewerken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRackModal" tabindex="-1" aria-labelledby="editRackLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRackLabel">Rek Bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-3">
                    <form id="editRackForm" onsubmit="updateRack(event)">
                        <input type="hidden" id="editRackId">
                        <div class="mb-3">
                            <label for="editRackName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editRackName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRackRows" class="form-label">Aantal rijen</label>
                            <input type="number" class="form-control" id="editRackRows" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bewerken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="productsDetailsModal" tabindex="-1" aria-labelledby="productsDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productsDetailsLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Naam</th>
                                <th>Beschrijving</th>
                                <th>Eenheidsprijs</th>
                                <th>Hoeveelheid</th>
                                <th>Leverancier</th>
                                <th>Locatie</th>
                            </tr>
                        </thead>
                        <tbody id="productsDetailsTableBody">
                            <!-- Rows will be dynamically added here -->
                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="supplierDetailsModal" tabindex="-1" aria-labelledby="supplierDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierDetailsLabel">Leverancier Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" id="supplierDetailsName"></h5>
                            <p class="card-text"><strong>Email:</strong> <span id="supplierDetailsEmail"></span></p>
                            <p class="card-text"><strong>Address:</strong> <span id="supplierDetailsAddress"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showMessage(type, text) {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
            setTimeout(() => {
                messageDiv.innerHTML = '';
            }, 3000);
        }

        function fetchProducts() {
            document.getElementById('ProductsTable').classList.remove('d-none');
            document.getElementById('SuppliersTable').classList.add('d-none');
            document.getElementById('RacksTable').classList.add('d-none');
            fetch('/api/products')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(products => {
                    const productsTableBody = document.getElementById('ProductsTableBody');
                    productsTableBody.innerHTML = '';

                    products.forEach(product => {
                        const productRow = document.createElement('tr');
                        productRow.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.description}</td>
                            <td>€${product.price.toFixed(2)}</td>
                            <td>${product.quantity}</td>
                            <td>
                                ${product.supplier.name} <br>
                                <button class="btn btn-secondary btn-sm w-100" onclick="openSupplierDetailsModal(${product.productId})">Details</button>
                            </td>
                            <td>
                                ${product.location.rack.name}: rij ${product.location.row}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="openProductEditModal(${product.productId})">Bewerken</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.productId})">Verwijderen</button>
                            </td>
                        `;
                        productsTableBody.appendChild(productRow);
                    });
                })
                .catch(error => {
                    console.error('Error fetching products:', error);
                    const productsTableBody = document.getElementById('ProductsTableBody');
                    productsTableBody.innerHTML = `<tr><td colspan="7" class="text-danger">Er is een fout opgetreden bij het ophalen van de producten.</td></tr>`;
                });
        }

        function fetchSuppliers() {
            document.getElementById('ProductsTable').classList.add('d-none');
            document.getElementById('SuppliersTable').classList.remove('d-none');
            document.getElementById('RacksTable').classList.add('d-none');

            fetch('api/suppliers')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(suppliers => {
                    const suppliersTableBody = document.getElementById('SuppliersTableBody');
                    suppliersTableBody.innerHTML = '';

                    suppliers.forEach(supplier => {
                        const supplierRow = document.createElement('tr');
                        supplierRow.innerHTML = `
                            <td>${supplier.name}</td>
                            <td>${supplier.email}</td>
                            <td>${supplier.address}</td>
                            <td>
                                ${supplier.products.length} producten <br>
                                <button class="btn btn-secondary btn-sm w-100" onclick="openSupplierProductDetailsModal(${supplier.supplierId})">Details</button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="openSupplierEditModal(${supplier.supplierId})">Bewerken</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteSupplier(${supplier.supplierId})">Verwijderen</button>
                            </td>
                        `;
                        suppliersTableBody.appendChild(supplierRow);
                    });
                })
                .catch(error => {
                    console.error('Error fetching suppliers:', error);
                    const suppliersTableBody = document.getElementById('SuppliersTableBody');
                    suppliersTableBody.innerHTML = `<tr><td colspan="4" class="text-danger">Er is een fout opgetreden bij het ophalen van de leveranciers.</td></tr>`;
                });
        }

        function fetchRacks() {
            document.getElementById('ProductsTable').classList.add('d-none');
            document.getElementById('SuppliersTable').classList.add('d-none');
            document.getElementById('RacksTable').classList.remove('d-none');
            fetch('api/racks')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(racks => {
                    const racksTableBody = document.getElementById('RacksTableBody');
                    racksTableBody.innerHTML = '';

                    racks.forEach(rack => {
                        const rackRow = document.createElement('tr');
                        rackRow.innerHTML = `
                            <td>${rack.name}</td>
                            <td>${rack.rows}</td>
                            <td>
                                ${rack.products.length} producten <br>
                                <button class="btn btn-secondary btn-sm w-100" onclick="openRackProductDetailsModal(${rack.rackId})">Details</button>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="openRackEditModal(${rack.rackId})">Bewerken</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteRack(${rack.rackId})">Verwijderen</button>
                            </td>
                        `;
                        racksTableBody.appendChild(rackRow);
                    });
                })
                .catch(error => {
                    console.error('Error fetching racks:', error);
                    const racksTableBody = document.getElementById('RacksTableBody');
                    racksTableBody.innerHTML = `<tr><td colspan="4" class="text-danger">Er is een fout opgetreden bij het ophalen van de rekken.</td></tr>`;
                });
        }

        function fetchSupplierNames() {
            fetch(`/api/suppliers/names`)
                .then(response => response.json())
                .then(suppliers => {
                    const supplierSelect = document.getElementById('newProductSupplier');
                    supplierSelect.innerHTML = '<option value="">Kies een leverancier</option>';
                    suppliers.forEach(supplier => {
                        const option = document.createElement('option');
                        option.value = supplier.supplierId;
                        option.innerText = `${supplier.name}`;
                        supplierSelect.appendChild(option);
                    });
                });
        }

        function fetchRackNames(elementId) {
            fetch(`/api/racks/names`)
                .then(response => response.json())
                .then(racks => {
                    const rackSelect = document.getElementById(elementId);
                    rackSelect.innerHTML = '<option value="">Kies een rek</option>';
                    racks.forEach(rack => {
                        const option = document.createElement('option');
                        option.value = rack.rackId;
                        option.innerText = `${rack.name}`;
                        rackSelect.appendChild(option);
                    });
                });
        }

        function fetchRackEmptyLocations(rackId, elementId) {
            fetch(`/api/racks/${rackId}/emptyLocations`)
                .then(response => response.json())
                .then(locations => {
                    const rowSelect = document.getElementById(elementId);
                    rowSelect.innerHTML = '<option value="">Kies een lege rij</option>';
                    locations.forEach(location => {
                        const option = document.createElement('option');
                        option.value = location.locationId;
                        option.innerText = `Rij ${location.row}`;
                        rowSelect.appendChild(option);
                    });
                });
        }

        function openProductEditModal(productId) {
            fetch(`/api/products/${productId}`)
            .then(response => response.json())
            .then(product => {
                    const editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                    editProductModal.show();

                    document.getElementById('editProductId').value = product.productId;
                    document.getElementById('editProductName').value = product.name;
                    document.getElementById('editProductDescription').value = product.description;
                    document.getElementById('editProductPrice').value = product.price;
                    document.getElementById('editProductQuantity').value = product.quantity;
                    document.getElementById('editSupplierId').value = product.supplier.supplierId;
                    document.getElementById('editProductSupplier').value = product.supplier.name;
                    document.getElementById('editLocationId').value = product.location.locationId;
                    document.getElementById('currentProductLocation').value = `${product.location.rack.name}: rij ${product.location.row}`;
                    document.getElementById('editProductRack').value = product.location.rack.rackId;
                    document.getElementById('editProductRow').value = product.location.locationId;
                })
                .catch(error => console.error('Error fetching phases details:', error));
        }

        function openSupplierEditModal(supplierId) {
            fetch(`/api/suppliers/${supplierId}`)
                .then(response => response.json())
                .then(supplier => {
                    const editSupplierModal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
                    editSupplierModal.show();

                    document.getElementById('editSupplierId').value = supplier.supplierId;
                    document.getElementById('editSupplierName').value = supplier.name;
                    document.getElementById('editSupplierEmail').value = supplier.email;
                    document.getElementById('editSupplierAddress').value = supplier.address;
                })
                .catch(error => console.error('Error fetching supplier details:', error));
        }

        function openRackEditModal(rackId) {
            fetch(`/api/racks/${rackId}`)
                .then(response => response.json())
                .then(rack => {
                    const editRackModal = new bootstrap.Modal(document.getElementById('editRackModal'));
                    editRackModal.show();

                    document.getElementById('editRackId').value = rack.rackId;
                    document.getElementById('editRackName').value = rack.name;
                    document.getElementById('editRackRows').value = rack.rows;
                })
                .catch(error => console.error('Error fetching rack details:', error));
        }

        function openSupplierProductDetailsModal(supplierId) {
            fetch(`/api/suppliers/${supplierId}/products`)
                .then(response => response.json())
                .then(products => {
                    const productsDetailsTableBody = document.getElementById('productsDetailsTableBody');
                    productsDetailsTableBody.innerHTML = '';

                    products.forEach(product => {
                        const productRow = document.createElement('tr');
                        productRow.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.description}</td>
                            <td>€${product.price.toFixed(2)}</td>
                            <td>${product.quantity}</td>
                            <td>${product.supplier.name}</td>
                            <td>${product.location.rack.name}: rij ${product.location.row}</td>
                        `;
                        productsDetailsTableBody.appendChild(productRow);
                    });

                    const productsDetailsModal = new bootstrap.Modal(document.getElementById('productsDetailsModal'));
                    productsDetailsModal.show();
                })
                .catch(error => console.error('Error fetching supplier products:', error));
        }

        function openRackProductDetailsModal(rackId) {
            fetch(`/api/racks/${rackId}/products`)
                .then(response => response.json())
                .then(products => {
                    const productsDetailsTableBody = document.getElementById('productsDetailsTableBody');
                    productsDetailsTableBody.innerHTML = '';

                    products.forEach(product => {
                        const productRow = document.createElement('tr');
                        productRow.innerHTML = `
                            <td>${product.name}</td>
                            <td>${product.description}</td>
                            <td>€${product.price.toFixed(2)}</td>
                            <td>${product.quantity}</td>
                            <td>${product.supplier.name}</td>
                            <td>${product.location.rack.name}: rij ${product.location.row}</td>
                        `;
                        productsDetailsTableBody.appendChild(productRow);
                    });

                    const productsDetailsModal = new bootstrap.Modal(document.getElementById('productsDetailsModal'));
                    productsDetailsModal.show();
                })
                .catch(error => console.error('Error fetching rack products:', error));
        }

        function openSupplierDetailsModal(productId) {
            fetch(`/api/products/${productId}/supplier`)
                .then(response => response.json())
                .then(supplier => {
                    const supplierDetailsModal = new bootstrap.Modal(document.getElementById('supplierDetailsModal'));
                    supplierDetailsModal.show();

                    document.getElementById('supplierDetailsName').innerText = supplier.name;
                    document.getElementById('supplierDetailsEmail').innerText = supplier.email;
                    document.getElementById('supplierDetailsAddress').innerText = supplier.address;
                })
                .catch(error => console.error('Error fetching supplier details:', error));
        }

        function updateProduct(event) {
            event.preventDefault();

            const productId = document.getElementById('editProductId').value;
            const name = document.getElementById('editProductName').value;
            const description = document.getElementById('editProductDescription').value;
            const price = document.getElementById('editProductPrice').value;
            const quantity = document.getElementById('editProductQuantity').value;
            const supplierId = document.getElementById('editSupplierId').value;
            const changeLocation = document.getElementById('editProductLocationRadio').value;
            let locationId = document.getElementById('editLocationId').value;

            if (changeLocation === '1') {
                locationId = document.getElementById('editProductRow').value;
            }


            fetch(`/api/products/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    description,
                    price,
                    quantity,
                    supplierId,
                    locationId
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to update product');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProducts();
                    document.getElementById('editProductForm').reset();
                    const editProductModal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                    editProductModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het bewerken van het product.');
                });
        }

        function updateSupplier(event) {
            event.preventDefault();

            const supplierId = document.getElementById('editSupplierId').value;
            const name = document.getElementById('editSupplierName').value;
            const email = document.getElementById('editSupplierEmail').value;
            const address = document.getElementById('editSupplierAddress').value;

            fetch(`/api/suppliers/${supplierId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    email,
                    address
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to update supplier');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchSuppliers();
                    document.getElementById('editSupplierForm').reset();
                    const editSupplierModal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                    editSupplierModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het bewerken van de leverancier.');
                });
        }

        function updateRack(event) {
            event.preventDefault();

            const rackId = document.getElementById('editRackId').value;
            const name = document.getElementById('editRackName').value;
            const rows = document.getElementById('editRackRows').value;

            fetch(`/api/racks/${rackId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    rows
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to update rack');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchRacks();
                    document.getElementById('editRackForm').reset();
                    const editRackModal = bootstrap.Modal.getInstance(document.getElementById('editRackModal'));
                    editRackModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het bewerken van het rek.');
                });
        }

        function deleteProduct(productId) {
            fetch(`/api/products/${productId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to delete project');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProducts();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van het project.');
                });
        }

        function deleteSupplier(supplierId) {
            fetch(`/api/suppliers/${supplierId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to delete supplier');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchSuppliers();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van de leverancier.');
                });
        }

        function deleteRack(rackId) {
            fetch(`/api/racks/${rackId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to delete rack');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchRacks();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van het rek.');
                });
        }

        function createProduct(event) {
            event.preventDefault();

            const name = document.getElementById('newProductName').value;
            const description = document.getElementById('newProductDescription').value;
            const price = document.getElementById('newProductPrice').value;
            const quantity = document.getElementById('newProductQuantity').value;
            const supplierId = document.getElementById('newProductSupplier').value;
            const locationId = document.getElementById('newProductRow').value;

            fetch('/api/products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    description,
                    price,
                    quantity,
                    supplierId,
                    locationId
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to create product');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProducts();
                    document.getElementById('newProductForm').reset();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van het product.');
                });
        }

        function createSupplier(event) {
            event.preventDefault();

            const name = document.getElementById('newSupplierName').value;
            const email = document.getElementById('newSupplierEmail').value;
            const address = document.getElementById('newSupplierAddress').value;

            fetch('/api/suppliers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    email,
                    address
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to create supplier');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchSuppliers();
                    document.getElementById('newSupplierForm').reset();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van de leverancier.');
                });
        }

        function createRack(event) {
            event.preventDefault();

            const name = document.getElementById('newRackName').value;
            const rows = document.getElementById('newRackRows').value;

            fetch('/api/racks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name,
                    rows
                })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to create rack');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchRacks();
                    document.getElementById('newRackForm').reset();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van het rek.');
                });
        }

        fetchProducts();
        document.getElementById('newProductModal').addEventListener('show.bs.modal', fetchSupplierNames());
        document.getElementById('newProductModal').addEventListener('show.bs.modal', fetchRackNames('newProductRack'));
        document.getElementById('newProductRack').addEventListener('change', (event) => {
            fetchRackEmptyLocations(event.target.value, 'newProductRow');
        });
        document.getElementById('editProductModal').addEventListener('show.bs.modal', fetchRackNames('editProductRack'));
        document.getElementById('editProductRack').addEventListener('change', (event) => {
            fetchRackEmptyLocations(event.target.value, 'editProductRow');
        });
    </script>
</body>
</html>
