<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasen voor Project</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div id="message"></div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 id="projectName">Fasen voor Project </h1>
            <button id="addPhaseButton" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPhaseModal">
                Nieuwe Fase
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Beschrijving</th>
                    <th>Startdatum</th>
                    <th>Einddatum</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody id="phasesTableBody">
                <!-- Rows will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <div class="container mt-5">
        <h2 id= WeatherForecastLabel>Weersvoorspelling</h2>
        <table class="table table-bordered" id="weatherTable">
            <thead>
                <tr id="weatherTableHeader">
                    <!-- Headers worden dynamisch toegevoegd -->
                </tr>
            </thead>
            <tbody>
                <tr id="weatherTableTemperature"></tr>
                <tr id="weatherTablePrecipitationProbability"></tr>
                <tr id="weatherTablePrecipitationAmount"></tr>
                <tr id="weatherTableWindSpeed"></tr>
                <!-- Rijen worden dynamisch toegevoegd -->
            </tbody>
        </table>
    </div>


    <!-- Add Phase Modal -->
    <div class="modal" tabindex="-1" id="addPhaseModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nieuwe Fase Aanmaken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPhaseForm" onsubmit="createPhase(event)">
                        <div class="mb-3">
                            <label for="phaseName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="newPhaseName" required>
                        </div>
                        <div class="mb-3">
                            <label for="phaseDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="newPhaseDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phaseStartDate" class="form-label">Startdatum</label>
                            <input type="Date" class="form-control" id="newPhaseStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="phaseEndDate" class="form-label">Einddatum</label>
                            <input type="Date" class="form-control" id="newPhaseEndDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Phase Modal -->
    <div class="modal" tabindex="-1" id="editPhaseModal" aria-labelledby="editPhaseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bewerk Fase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPhaseForm" onsubmit="updatePhase(event)">
                        <input type="hidden" id="editPhaseId">
                        <div class="mb-3">
                            <label for="editPhaseName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editPhaseName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhaseDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="editPhaseDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editPhaseStartDate" class="form-label">Startdatum</label>
                            <input type="Date" class="form-control" id="editPhaseStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhaseEndDate" class="form-label">Einddatum</label>
                            <input type="Date" class="form-control" id="editPhaseEndDate" required>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
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
                document.getElementById('projectName').innerText = `Fasen voor ${project.name}`;
                projectLocation = project.location;
            });
        }

        function fetchPhases() {
            fetch(`/api/projects/${projectId}/phases`)
                .then(response => response.json())
                .then(phases => {
                    const phaseList = document.getElementById('phasesTableBody');
                    phaseList.innerHTML = '';
                    phases.forEach(phase => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${phase.name}</td>
                            <td>${phase.description}</td>
                            <td>${phase.startDate}</td>
                            <td>${phase.endDate}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="openEditModal(${phase.phaseId})">Bewerken</button>
                                <button class="btn btn-danger btn-sm" onclick="deletePhase(${phase.phaseId})">Verwijderen</button>
                            </td>
                        `;
                        phaseList.appendChild(row);
                    });
                });
        }

        function openEditModal(phaseId) {
            fetch(`/api/projects/${projectId}/phases/${phaseId}`)
                .then(response => response.json())
                .then(phase => {
                    document.getElementById('editPhaseId').value = phaseId;
                    document.getElementById('editPhaseName').value = phase.name;
                    document.getElementById('editPhaseDescription').value = phase.description;
                    document.getElementById('editPhaseStartDate').value = phase.startDate;
                    document.getElementById('editPhaseEndDate').value = phase.endDate;

                    const editPhaseModal = new bootstrap.Modal(document.getElementById('editPhaseModal'));
                    editPhaseModal.show();
                })
                .catch(error => console.error('Error fetching phases details:', error));
        }

        function updatePhase(event) {
            event.preventDefault();
            const phaseId = document.getElementById('editPhaseId').value;
            const name = document.getElementById('editPhaseName').value;
            const description = document.getElementById('editPhaseDescription').value;
            const startDate = document.getElementById('editPhaseStartDate').value;
            const endDate = document.getElementById('editPhaseEndDate').value;

            fetch(`/api/projects/${projectId}/phases/${phaseId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, description, startDate, endDate })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to update project');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchPhases();
                    const editPhaseModal = bootstrap.Modal.getInstance(document.getElementById('editPhaseModal'));
                    editPhaseModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het bijwerken van de fase.');
                });
        }

        function deletePhase(phaseId) {
            fetch(`/api/projects/${projectId}/phases/${phaseId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to delete phase');
                    }
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchPhases();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van de fase.');
                });
        }

        function createPhase(event) {
            event.preventDefault();
            const name = document.getElementById('newPhaseName').value;
            const description = document.getElementById('newPhaseDescription').value;
            const startDate = document.getElementById('newPhaseStartDate').value;
            const endDate = document.getElementById('newPhaseEndDate').value;

            fetch(`/api/projects/${projectId}/phases`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, description, startDate, endDate })
            })
                .then(data => {
                    showMessage('success', data.message);
                    fetchPhases();
                    document.getElementById('addPhaseModal').reset();
                    const addPhaseModal = bootstrap.Modal.getInstance(document.getElementById('addPhaseModal'));
                    addPhaseModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van het project.');
                });
        }

        function fetchWeatherForecast() {
            fetch(`/api/projects/${projectId}`)
                .then(response => response.json())
                .then(project => {
                    const location = project.location;
                    document.getElementById('WeatherForecastLabel').innerText = `Weersvoorspelling voor ${location}`;
                    fetch(`/api/weather/${location}`)
                        .then(response => response.json())
                        .then(data => {
                            const headerRow = document.getElementById('weatherTableHeader');
                            const temperatureRow = document.getElementById('weatherTableTemperature');
                            const precipitationProbabilityRow = document.getElementById('weatherTablePrecipitationProbability');
                            const precipitationAmountRow = document.getElementById('weatherTablePrecipitationAmount');
                            const windSpeedRow = document.getElementById('weatherTableWindSpeed');

                            // Maak de tabel leeg voordat nieuwe data wordt toegevoegd
                            headerRow.innerHTML = '';
                            temperatureRow.innerHTML = '';
                            precipitationProbabilityRow.innerHTML = '';
                            precipitationAmountRow.innerHTML = '';
                            windSpeedRow.innerHTML = '';


                            const headers = ['Dag', 'Temperatuur', 'Neerslagkans', 'Neerslaghoeveelheid', 'Windsnelheid'];

                            headers.forEach(header => {
                                if (header === 'Dag') {
                                    const th = document.createElement('th');
                                    th.textContent = header;
                                    headerRow.appendChild(th);;
                                }
                                if (header === 'Temperatuur') {
                                    const td = document.createElement('td');
                                    td.textContent = header;
                                    temperatureRow.appendChild(td);
                                }
                                if (header === 'Neerslagkans') {
                                    const td = document.createElement('td');
                                    td.textContent = header;
                                    precipitationProbabilityRow.appendChild(td);
                                }
                                if (header === 'Neerslaghoeveelheid') {
                                    const td = document.createElement('td');
                                    td.textContent = header;
                                    precipitationAmountRow.appendChild(td);
                                }
                                if (header === 'Windsnelheid') {
                                    const td = document.createElement('td');
                                    td.textContent = header;
                                    windSpeedRow.appendChild(td);
                                }
                                data.forEach(entry => {
                                    if (header === 'Dag') {
                                    const day = new Date(entry.day).toLocaleDateString('nl-NL', { weekday: 'long', day: 'numeric', month: 'long' });
                                    const th = document.createElement('th');
                                    th.textContent = day;
                                    headerRow.appendChild(th);
                                    }
                                    if (header === 'Temperatuur') {
                                    const temperature = `${entry.temperature.toFixed(1)} °C`;
                                    const td = document.createElement('td');
                                    td.textContent = temperature;
                                    temperatureRow.appendChild(td);
                                    }
                                    if (header === 'Neerslagkans') {
                                    const precipitation = `${entry.precipitationProbability.toFixed(0)}%`;
                                    const td = document.createElement('td');
                                    td.textContent = precipitation;
                                    precipitationProbabilityRow.appendChild(td);
                                    }
                                    if (header === 'Neerslaghoeveelheid') {
                                    const precipitationAmount = `${entry.rainAccumulation.toFixed(1)} mm`;
                                    const td = document.createElement('td');
                                    td.textContent = precipitationAmount;
                                    precipitationAmountRow.appendChild(td);
                                    }
                                    if (header === 'Windsnelheid') {
                                    const windSpeed = `${entry.windSpeed.toFixed(1)} m/s`;
                                    const td = document.createElement('td');
                                    td.textContent = windSpeed;
                                    windSpeedRow.appendChild(td);
                                    }
                                });

                            });

                        })
                        .catch(error => console.error('Error fetching weather data:', error));
                });
        }


        fetchProjectInformation();
        fetchPhases();
        fetchWeatherForecast();
    </script>
</body>


</html>
