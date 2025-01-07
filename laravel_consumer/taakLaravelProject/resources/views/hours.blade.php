<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gewerkte Uren</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <div id="message"></div>
        <h1 id="projectName">Geboekte uren voor project</h1>
        <div class="d-flex justify-content-end mb-4">
            <button id="addHourButton" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addHoursModal">
                Uren Boeken
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Totaal aantal uur</th>
                    <th>Functie</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="hoursTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <!-- Add Hours Modal -->
    <div class="modal" tabindex="-1" id="addHoursModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nieuw gewerkte uren boeken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addHourForm" onsubmit="bookHours(event)">
                        <div class="mb-3">
                            <label for="workerName" class="form-label">Kies een werknemer</label>
                            <select class="form-select" id="newWorkerName" required>
                                <option value="">Kies een werknemer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hoursWorked" class="form-label  ">Aantal uur gewerkt</label>
                            <input type="number" class="form-control" id="newHoursWorked" required>
                        </div>
                        <div class="mb-3">
                            <label for="hoursComment" class="form-label">Opmerking</label>
                            <textarea class="form-control" id="newHoursComment" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="hoursDate" class="form-label">Datum gewerkt</label>
                            <input type="Date" class="form-control" id="newHoursDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="workedHoursDetailsModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Details gewerkte uren</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Uur gewerkt</th>
                            <th>Opmerking</th>
                            <th>Datum</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody id="DetailsTableBody">
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="deleteHourModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uren verwijderen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deleteHourForm">
                        <label id="deleteHourMessage" class="form-label"></label>
                        <div class="mb-3">
                            <label for="deleteHourPassword" class="form-label">Wachtwoord</label>
                            <input type="password" class="form-control" id="deleteHourPassword" required>
                        </div>
                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const projectId = {{ $projectId }};

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
                document.getElementById('projectName').innerText = `Geboekte uren voor ${project.name}`;
            });
        }

        function fetchHoursWorked() {
            fetch(`/api/projects/${projectId}/hours`)
                .then(response => response.json())
                .then(hoursWorked => {
                    const hoursWorkedList = document.getElementById('hoursTableBody');
                    hoursWorkedList.innerHTML = '';
                    hoursWorked.forEach(hourWorked => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${hourWorked.name + ' ' + hourWorked.surname}</td>
                        <td>${hourWorked.workedHours}</td>
                        <td>${hourWorked.function}</td>
                        <td>
                            <button class="btn btn-secondary" onclick="openDetailsModal(${hourWorked.workerId})">Bekijk details</button>
                        </td>
                        `;
                        hoursWorkedList.appendChild(row);
                    });
                });
        }

        function fetchWorkers() {
            fetch(`/api/workers`)
                .then(response => response.json())
                .then(workers => {
                    const workerSelect = document.getElementById('newWorkerName');
                    workerSelect.innerHTML = '<option value="">Kies een werknemer</option>';
                    workers.forEach(worker => {
                        const option = document.createElement('option');
                        option.value = worker.id;
                        option.innerText = `${worker.name} ${worker.surname}`;
                        workerSelect.appendChild(option);
                    });
                });
        }

        function openDetailsModal(workerId) {
            fetch(`/api/projects/${projectId}/hours/${workerId}`)
                .then(response => response.json())
                .then(hoursWorked => {
                    const workedHoursDetailsModal = new bootstrap.Modal(document.getElementById('workedHoursDetailsModal'));
                    workedHoursDetailsModal.show();
                    const detailsTableBody = document.getElementById('DetailsTableBody');
                    detailsTableBody.innerHTML = '';
                    hoursWorked.forEach(hourWorked => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${hourWorked.name + ' ' + hourWorked.surname}</td>
                        <td>${hourWorked.hours}</td>
                        <td>${hourWorked.comment}</td>
                        <td>${hourWorked.date}</td>
                        <td>
                            <button class="btn btn-danger" onclick="openDeleteHourModal(${hourWorked.whId})">Verwijderen</button>
                        </td>
                        `;
                        detailsTableBody.appendChild(row);
                    });
                });
        }

        function openDeleteHourModal(whId) {
            const deleteHourModal = new bootstrap.Modal(document.getElementById('deleteHourModal'));
            deleteHourModal.show();

            const deleteForm = document.getElementById('deleteHourForm');
            deleteForm.onsubmit = event => {
                event.preventDefault();
                const password = document.getElementById('deleteHourPassword').value;
                deleteWorkedHour(whId, password);
            };
        }

        function deleteWorkedHour(whId, password) {
            fetch(`/api/hours/${whId}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ password })
            })
            .then(response => {
                // Verkrijg de status en parse de JSON
                return response.json().then(data => ({
                    status: response.status,
                    data: data
                }));
            })
            .then(({ status, data }) => {
                if (status === 200) { // Vergelijking met === voor exacte match
                    showMessage('success', 'Geboekte uren succesvol verwijderd.');
                    fetchHoursWorked();

                    // Verberg modals
                    document.getElementById('deleteHourForm').reset();
                    const deleteHourModal = bootstrap.Modal.getInstance(document.getElementById('deleteHourModal'));
                    deleteHourModal.hide();

                    const workedHoursDetailsModal = bootstrap.Modal.getInstance(document.getElementById('workedHoursDetailsModal'));
                    workedHoursDetailsModal.hide();
                } else {
                    // Toon foutmelding
                    const messageDiv = document.getElementById('deleteHourMessage');
                    messageDiv.innerHTML = `<div class="alert alert-danger">Wachtwoord is onjuist!</div>`;
                }
            })
            .catch(error => {
                console.error(error);
                showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van de uren.');
            });
        }

        function bookHours(event) {
            event.preventDefault();
            const fullName = document.getElementById('newWorkerName').options[document.getElementById('newWorkerName').selectedIndex].text;
            const hours = document.getElementById('newHoursWorked').value;
            const comment = document.getElementById('newHoursComment').value;
            const date = document.getElementById('newHoursDate').value;

            fetch(`/api/projects/${projectId}/hours`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fullName, hours, comment, date })
            })
                .then(data => {
                    showMessage('success', 'Uren succesvol geboekt.');
                    fetchHoursWorked();
                    document.getElementById('addHourForm').reset();
                    const addHoursModal = bootstrap.Modal.getInstance(document.getElementById('addHoursModal'));
                    addHoursModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van het project.');
                });
        }

        fetchProjectInformation();
        fetchHoursWorked();
        document.getElementById('addHoursModal').addEventListener('show.bs.modal', fetchWorkers);
    </script>
</body>


</html>
