<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculatie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <h1 id="projectName">Calculatie voor project</h1>
        <div class="d-flex justify-content-end mb-4">
            <button id="addCalculationsButton" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCalculationsModal">
                Calculaties toevoegen
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Artikel Nr</th>
                    <th>Artikel Omschrijving</th>
                    <th>Meetcode</th>
                    <th>Eenheid</th>
                    <th>Hoeveelheid</th>
                    <th>Eenheidsprijs</th>
                    <th>Totaal</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="calculationsTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <!-- Add Calculations Modal -->
    <div class="modal fade " id="addCalculationsModal" tabindex="-1" aria-labelledby="addCalculationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCalculationsModalLabel">Calculaties toevoegen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-success" onclick="addCalculationRow()">
                            Rij toevoegen
                        </button>
                    </div>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Artikel Nr</th>
                                <th>Artikel Omschrijving</th>
                                <th>Meetcode</th>
                                <th>Eenheid</th>
                                <th>Hoeveelheid</th>
                                <th>Eenheidsprijs</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody id="calculationRows">
                            <!-- Rows will be dynamically added here -->
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-success" onclick="addCalculations()">Verzenden</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCalculationModal" tabindex="-1" aria-labelledby="editCalculationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCalculationModalLabel">Calculatie bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="updateCalculation(event)">
                        <input type="hidden" id="editCalculationId">
                        <div class="mb-3">
                            <label for="editArticleId" class="form-label text-start">Artikel Nr</label>
                            <input type="text" class="form-control" id="editArticleId" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label text-start">Artikel Omschrijving</label>
                            <input type="text" class="form-control" id="editDescription" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMeasurementType" class="form-label text-start">Meetcode</label>
                            <select class="form-select" id="editMeasurementType" required>
                                <option value="1">FH</option>
                                <option value="2">VH</option>
                                <option value="3">SOG</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editMeasurementUnit" class="form-label text-start">Eenheid</label>
                            <input type="text" class="form-control" id="editMeasurementUnit" required>
                        </div>
                        <div class="mb-3">
                            <label for="editQuantity" class="form-label text-start">Hoeveelheid</label>
                            <input type="number" step="0.1" class="form-control" id="editQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPricePerUnit" class="form-label text-start">Eenheidsprijs</label>
                            <input type="number" step="0.01" class="form-control" id="editPricePerUnit" required>
                        </div>
                        <button type="submit" class="btn btn-success">Opslaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const projectId = {{ $projectId }};

        console.log(projectId);

        // Functie om naam van project op te halen
        function fetchProjectInformation() {
            fetch(`/api/projects/${projectId}`)
            .then(response => response.json())
            .then(project => {
                document.getElementById('projectName').innerText = `Calculatie voor project: ${project.name}`;
            });
        }

        // Functie om meetstaatberekeningen op te halen en in tabel te tonen
        function fetchCalculations() {
            // GET request om meetstaatberekeningen op te halen
            fetch(`/api/projects/${projectId}/calculations`)
                .then(response => response.json())
                .then(calculations => {
                    // console.log(calculations);
                    const calculationsTableBody = document.getElementById('calculationsTableBody');
                    calculationsTableBody.innerHTML = '';
                    calculations.forEach(calculation => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${calculation.articleId}</td>
                        <td>${calculation.description}</td>
                        <td>${calculation.measurementType}</td>
                        <td>${calculation.measurementUnit}</td>
                        <td>${calculation.quantity.toFixed(2)}</td>
                        <td>€${calculation.pricePerUnit.toFixed(2)}</td>
                        <td>€${calculation.totalPrice.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-primary" onclick="openEditCalculation(${calculation.calculationId})">Bewerken</button>
                            <button class="btn btn-danger" onclick="deleteCalculation(${calculation.calculationId})">Verwijderen</button>
                        </td>
                        `;
                        calculationsTableBody.appendChild(row);
                    });
                });
        }

        // Functie om rij toe te voegen in modal voor nieuwe meetstaatberekeningen
        function addCalculationRow() {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td>
                    <select contenteditable="true">
                        <option value="1">FH</option>
                        <option value="2">VH</option>
                        <option value="3">SOG</option>
                    </select>
                </td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td contenteditable="true"></td>
                <td>
                    <button class="btn btn-danger" onclick="removeCalculationRow(this)">Verwijderen</button>
                </td>
            `;
            document.getElementById('calculationRows').appendChild(row);
        }

        // Functie om rij te verwijderen in modal voor nieuwe meetstaatberekeningen
        function removeCalculationRow(button) {
            button.closest('tr').remove();
        }

        // Functie om modal voor nieuwe meetstaatberekeningen te initialiseren
        function initiateAddcalculations() {
            document.getElementById('calculationRows').innerHTML = '';
            for (let i = 0; i < 5; i++) {
                addCalculationRow();
            }
        }

        // Functie om meetstaatberekeningen toe te voegen
        function addCalculations() {
            const calculationRows = document.querySelectorAll('#calculationRows tr');
            const calculations = [];
            calculationRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const calculation = {
                    articleId: parseInt(cells[0].innerText, 10),
                    description: cells[1].innerText,
                    measurementType: cells[2].querySelector('select').selectedOptions[0].innerText,
                    measurementUnit: cells[3].innerText,
                    quantity: parseInt(cells[4].innerText, 10),
                    pricePerUnit: parseFloat(cells[5].innerText),
                };

                // Controleren of alle velden zijn ingevuld van een rij
                if (isNaN(calculation.articleId) || !calculation.description || !calculation.measurementType || !calculation.measurementUnit || isNaN(calculation.quantity) || isNaN(calculation.pricePerUnit)) {
                    row.remove();
                } else {
                    calculations.push(calculation);
                }

            });

            // POST request om meetstaatberekeningen toe te voegen
            fetch(`/api/projects/${projectId}/calculations`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(calculations),
            })
            .then(response => response.json())
            .then(data => {
                fetchCalculations();
                document.getElementById('addCalculationsModal').querySelector('.btn-close').click();
                document.getElementById('calculationRows').innerHTML = '';

            });
        }

        // Functie om een meetstaatberekening te verwijderen
        function deleteCalculation(calculationId) {
            // DELETE request om meetstaatberekening te verwijderen
            fetch(`/api/calculations/${calculationId}`, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                fetchCalculations();
            });
        }

        // Functie om modal voor bewerken van meetstaatberekening te openen
        function openEditCalculation(calculationId) {
            fetch(`/api/calculations/${calculationId}`)
                .then(response => response.json())
                .then(calculation => {
                    document.getElementById('editCalculationId').value = calculation.calculationId;
                    document.getElementById('editArticleId').value = calculation.articleId;
                    document.getElementById('editDescription').value = calculation.description;
                    document.getElementById('editMeasurementType').value = calculation.measurementType;
                    const options = document.getElementById('editMeasurementType').options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].text === calculation.measurementType) {
                            options[i].selected = true;
                            break;
                        }
                    }
                    document.getElementById('editMeasurementUnit').value = calculation.measurementUnit;
                    document.getElementById('editQuantity').value = calculation.quantity;
                    document.getElementById('editPricePerUnit').value = calculation.pricePerUnit;

                    const editCalculationModal = new bootstrap.Modal(document.getElementById('editCalculationModal'));
                    editCalculationModal.show();
                });
        }

        // Functie om meetstaatberekening te bewerken
        function updateCalculation(event) {
            event.preventDefault();
            const calculationId = document.getElementById('editCalculationId').value;
            const calculation = {
                projectId: projectId,
                articleId: parseInt(document.getElementById('editArticleId').value, 10),
                description: document.getElementById('editDescription').value,
                measurementType: document.getElementById('editMeasurementType').selectedOptions[0].innerText,
                measurementUnit: document.getElementById('editMeasurementUnit').value,
                quantity: parseInt(document.getElementById('editQuantity').value, 10),
                pricePerUnit: parseFloat(document.getElementById('editPricePerUnit').value)
            };

            // PUT request om meetstaatberekening te bewerken
            fetch(`/api/calculations/${calculationId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(calculation),
            })
            .then(response => response.json())
            .then(data => {
                fetchCalculations();
                document.getElementById('editCalculationModal').querySelector('.btn-close').click();
            });
        }

        fetchProjectInformation();
        fetchCalculations();

        document.getElementById('addCalculationsButton').addEventListener('click', initiateAddcalculations);
    </script>
</body>
</html>
