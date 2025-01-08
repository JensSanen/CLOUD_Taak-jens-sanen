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
        <div id="message"></div>
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

    <script>
        const projectId = {{ $projectId }};

        console.log(projectId);

        function showMessage(type, text) {
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
            setTimeout(() => {
                messageDiv.innerHTML = '';
            }, 3000);
        }

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
                            <button class="btn btn-primary" onclick="editCalculation(${calculation.articleId})">Bewerken</button>
                            <button class="btn btn-danger" onclick="deleteCalculation(${calculation.articleId})">Verwijderen</button>
                        </td>
                        `;
                        calculationsTableBody.appendChild(row);
                    });
                });
        }

        fetchProjectInformation();
        fetchCalculations();
    </script>
</body>
</html>
