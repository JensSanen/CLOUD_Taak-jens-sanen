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

    <script>
        const projectId = {{ $projectId }};

        console.log(projectId);

        function fetchProjectInformation() {
            fetch(`/api/projects/${projectId}`)
            .then(response => response.json())
            .then(project => {
                document.getElementById('projectName').innerText = `Calculatie voor project: ${project.name}`;
            });
        }

        function fetchCalculations() {
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
                        <td>${calculation.quantity}</td>
                        <td>€${calculation.pricePerUnit}</td>
                        <td>€${calculation.totalPrice}</td>
                        <td>
                            <button class="btn btn-primary" onclick="editCalculation(${calculation.calculationId})">Bewerken</button>
                            <button class="btn btn-danger" onclick="deleteCalculation(${calculation.calculationId})">Verwijderen</button>
                        </td>
                        `;
                        calculationsTableBody.appendChild(row);
                    });
                });
        }

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

        function removeCalculationRow(button) {
            button.closest('tr').remove();
        }

        function initiateAddcalculations() {
            document.getElementById('calculationRows').innerHTML = '';
            for (let i = 0; i < 5; i++) {
                addCalculationRow();
            }
        }

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

                // Check if any value is missing
                if (isNaN(calculation.articleId) || !calculation.description || !calculation.measurementType || !calculation.measurementUnit || isNaN(calculation.quantity) || isNaN(calculation.pricePerUnit)) {
                    row.remove();
                } else {
                    calculations.push(calculation);
                }

            });

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

        fetchProjectInformation();
        fetchCalculations();

        document.getElementById('addCalculationsButton').addEventListener('click', initiateAddcalculations);
    </script>
</body>
</html>
