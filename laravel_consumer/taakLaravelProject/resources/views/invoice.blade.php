<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factuur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 id="projectName">Factuur voor project</h1>

        <div id="invoiceDetails" class="mt-4"></div>
    </div>

    <script>
        const projectId = {{ $projectId }};
        const invoiceContainer = document.getElementById('invoiceDetails');

        function fetchProjectInformation() {
            fetch(`/api/projects/${projectId}`)
                .then(response => response.json())
                .then(project => {
                    document.getElementById('projectName').innerText = `Factuur voor project: ${project.name}`;
                });
        }

        function fetchInvoiceData() {
            fetch(`/api/projects/${projectId}/invoice`)
                .then(response => response.text())
                .then(xmlString => {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(xmlString, "application/xml");

                    const invoice = xmlDoc.querySelector('*|invoice');
                    const project = invoice.querySelector('*|project');
                    const phases = invoice.querySelectorAll('*|phases');
                    const workers = invoice.querySelectorAll('*|workers');
                    const calculations = invoice.querySelectorAll('*|calculations');

                    let htmlContent = `
                        <div class="mb-5">
                            <h2>Projectinformatie</h2>
                            <div class="mb-3">
                                <strong>Naam:</strong> ${project.querySelector('*|name').textContent}
                            </div>
                            <div class="mb-3">
                                <strong>Beschrijving:</strong> ${project.querySelector('*|description').textContent}
                            </div>
                            <div class="mb-3">
                                <strong>Locatie:</strong> ${project.querySelector('*|location').textContent}
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong> ${project.querySelector('*|status').textContent}
                            </div>
                        </div>
                    `;

                    htmlContent += `<div class="mb-5">
                        <h2>Fasen</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Naam</th>
                                    <th>Beschrijving</th>
                                    <th>Startdatum</th>
                                    <th>Einddatum</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    phases.forEach(phase => {
                        htmlContent += `
                            <tr>
                                <td>${phase.querySelector('*|name').textContent}</td>
                                <td>${phase.querySelector('*|description').textContent}</td>
                                <td>${phase.querySelector('*|startDate').textContent}</td>
                                <td>${phase.querySelector('*|endDate').textContent}</td>
                            </tr>
                        `;
                    });
                    htmlContent += `</tbody></table></div>`;

                    htmlContent += `<div class="mb-5">
                        <h2>Werknemers</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Naam</th>
                                    <th>Functie</th>
                                    <th>Gewerkte uren</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    workers.forEach(worker => {
                        htmlContent += `
                            <tr>
                                <td>${worker.querySelector('*|name').textContent} ${worker.querySelector('*|surname').textContent}</td>
                                <td>${worker.querySelector('*|function').textContent}</td>
                                <td>${worker.querySelector('*|workedHours').textContent}</td>
                            </tr>
                        `;
                    });
                    htmlContent += `</tbody></table></div>`;

                    htmlContent += `<div class="mb-5">
                        <h2>Meetstaat</h2>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Artikel nummer</th>
                                    <th>Beschrijving</th>
                                    <th>Type</th>
                                    <th>Eenheid</th>
                                    <th>Hoeveelheid</th>
                                    <th>Prijs per eenheid</th>
                                    <th>Totale prijs</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    calculations.forEach(calc => {
                        htmlContent += `
                            <tr>
                                <td>${calc.querySelector('*|articleId').textContent}</td>
                                <td>${calc.querySelector('*|description').textContent}</td>
                                <td>${calc.querySelector('*|measurementType').textContent}</td>
                                <td>${calc.querySelector('*|measurementUnit').textContent}</td>
                                <td>${calc.querySelector('*|quantity').textContent}</td>
                                <td>€${calc.querySelector('*|pricePerUnit').textContent}</td>
                                <td>€${calc.querySelector('*|totalPrice').textContent}</td>
                            </tr>
                        `;
                    });
                    htmlContent += `</tbody></table></div>`;

                    invoiceContainer.innerHTML = htmlContent;
                })
                .catch(error => console.error('Error fetching invoice data:', error));
        }

        fetchProjectInformation();
        fetchInvoiceData();
    </script>
</body>
</html>
