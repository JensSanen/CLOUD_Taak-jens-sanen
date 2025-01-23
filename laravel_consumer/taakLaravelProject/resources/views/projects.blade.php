<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projecten</title>
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
        <h1 class="text-center mb-4">Projecten Overzicht</h1>
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                Nieuw Project
            </button>
        </div>
        <div id="projectList" class="row">
            <!-- Projecten worden hier geladen via JavaScript -->
        </div>
    </div>

    <!-- Modal voor het aanmaken van een nieuw project -->
    <div class="modal fade" id="newProjectModal" tabindex="-1" aria-labelledby="newProjectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProjectLabel">Nieuw Project Aanmaken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newProjectForm" onsubmit="createProject(event)">
                        <div class="mb-3">
                            <label for="newProjectName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="newProjectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProjectDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="newProjectDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="newProjectLocation" class="form-label">Locatie</label>
                            <input type="text" class="form-control" id="newProjectLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProjectStatus" class="form-label">Status</label>
                            <select id="newProjectStatus" class="form-select" required>
                                <option value="ACTIVE">Active</option>
                                <option value="COMPLETED">Completed</option>
                                <option value="CANCELLED">Cancelled</option>
                                <option value="PAUSED">Paused</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal voor het bewerken van een project -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectLabel">Bewerk Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm" onsubmit="updateProject(event)">
                        <input type="hidden" id="editProjectId">
                        <div class="mb-3">
                            <label for="editProjectName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editProjectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="editProjectDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectLocation" class="form-label">Locatie</label>
                            <input type="text" class="form-control" id="editProjectLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectStatus" class="form-label">Status</label>
                            <select id="editProjectStatus" class="form-select" required>
                                <option value="ACTIVE">Active</option>
                                <option value="COMPLETED">Completed</option>
                                <option value="CANCELLED">Cancelled</option>
                                <option value="PAUSED">Paused</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </form>
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

        function fetchProjects() {
            fetch('/api/projects')
                .then(response => response.json())
                .then(projects => {
                    const projectList = document.getElementById('projectList');
                    projectList.innerHTML = '';
                    projects.forEach(project => {
                        const projectCard = document.createElement('div');
                        projectCard.className = 'col-md-6';
                        projectCard.innerHTML = `
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><strong>${project.name}</strong></h5>
                                    <p class="card-text">${project.description}</p>
                                    <p class="card-text"><strong>Locatie:</strong> ${project.location}</p>
                                    <p class="card-text"><strong>Status:</strong> ${project.status}</p>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-grid gap-2" style="grid-template-columns: repeat(2, 1fr);">
                                                <button class="btn btn-primary btn-sm" onclick="openProjectPhases(${project.projectId})">Fases</button>
                                                <button class="btn btn-primary btn-sm" onclick="openProjectHours(${project.projectId})">Gewerkte uren</button>
                                                <button class="btn btn-primary btn-sm" onclick="openCalculations(${project.projectId})">Calculatie</button>
                                                <button class="btn btn-primary btn-sm"
                                                onclick="openInvoice(${project.projectId})">Factuur</button>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-secondary btn-sm" onclick="openEditModal(${project.projectId})">Bewerken</button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteProject(${project.projectId})">Verwijderen</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        projectList.appendChild(projectCard);
                    });
                })
                .catch(error => console.error('Error fetching projects:', error));
        }

        function openEditModal(projectId) {
            fetch(`/api/projects/${projectId}`)
                .then(response => response.json())
                .then(project => {
                    document.getElementById('editProjectId').value = project.projectId;
                    document.getElementById('editProjectName').value = project.name;
                    document.getElementById('editProjectDescription').value = project.description;
                    document.getElementById('editProjectLocation').value = project.location;
                    document.getElementById('editProjectStatus').value = project.status;

                    const editProjectModal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                    editProjectModal.show();
                })
                .catch(error => console.error('Error fetching project details:', error));
        }

        function openProjectPhases(projectId) {
            const projectPhasesURL = `/projects/${projectId}/phases`;
            window.open(projectPhasesURL, '_blank');
        }

        function openProjectHours(projectId) {
            const projectHoursURL = `/projects/${projectId}/hours`;
            window.open(projectHoursURL, '_blank');
        }

        function openCalculations(projectId) {
            const projectCalculationsURL = `/projects/${projectId}/calculations`;
            window.open(projectCalculationsURL, '_blank');
        }

        function openInvoice(projectId) {
            const projectInvoiceURL = `/projects/${projectId}/invoice`;
            window.open(projectInvoiceURL, '_blank');
        }

        function updateProject(event) {
            event.preventDefault();
            const projectId = document.getElementById('editProjectId').value;
            const name = document.getElementById('editProjectName').value;
            const description = document.getElementById('editProjectDescription').value;
            const location = document.getElementById('editProjectLocation').value;
            const status = document.getElementById('editProjectStatus').value;

            fetch(`/api/projects/${projectId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, description, location, status })
            })
                .then(response => {
                    if (response.ok)
                        return response.json();
                    else
                        throw new Error('Failed to update project');
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProjects();
                    const editProjectModal = bootstrap.Modal.getInstance(document.getElementById('editProjectModal'));
                    editProjectModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het bijwerken van het project.');
                });
        }

        function deleteProject(projectId) {
            fetch(`/api/projects/${projectId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to delete project');
                    }
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProjects();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het verwijderen van het project.');
                });
        }

        function createProject(event) {
            event.preventDefault();
            const name = document.getElementById('newProjectName').value;
            const description = document.getElementById('newProjectDescription').value;
            const location = document.getElementById('newProjectLocation').value;
            const status = document.getElementById('newProjectStatus').value;

            fetch('/api/projects', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, description, location, status })
            })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        throw new Error('Failed to create project');
                    }
                })
                .then(data => {
                    showMessage('success', data.message);
                    fetchProjects();
                    document.getElementById('newProjectForm').reset();
                    const newProjectModal = bootstrap.Modal.getInstance(document.getElementById('newProjectModal'));
                    newProjectModal.hide();
                })
                .catch(error => {
                    console.error(error);
                    showMessage('danger', 'Er is een fout opgetreden bij het aanmaken van het project.');
                });
        }

        fetchProjects();
    </script>
</body>
</html>
