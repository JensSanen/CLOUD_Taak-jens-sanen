<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projecten en Fasen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="d-flex justify-content-start mb-4">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                Nieuw Project
            </button>
        </div>
        <h1 class="text-center mb-4">Projecten Overzicht</h1>
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
                <div class="modal-body
                    <form id="newProjectForm">
                        <div class="mb-3">
                            <label for="newProjectName" class="form-label
                                Naam
                            </label>
                            <input type="text" class="form-control" id="newProjectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newProjectDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="newProjectDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Aanmaken</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal voor het aanpassen van project attributen -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectLabel">Project Aanpassen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        <input type="hidden" id="editProjectId">
                        <div class="mb-3">
                            <label for="editProjectName" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="editProjectName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectDescription" class="form-label">Beschrijving</label>
                            <textarea class="form-control" id="editProjectDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal voor fasen -->
    <div class="modal fade" id="phaseModal" tabindex="-1" aria-labelledby="phaseLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phaseLabel">Fasen van het project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="phaseList" class="list-group">
                        <!-- Fasen worden hier geladen via JavaScript -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function createProject() {
            const newProjectForm = document.getElementById('newProjectForm');
            const formData = new FormData(newProjectForm);
            fetch('/projectsAPI', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newProjectModal = new bootstrap.Modal(document.getElementById('newProjectModal'));
                        newProjectModal.hide();
                        fetchProjects();
                    }
                })
                .catch(error => console.error('Error creating project:', error));
        }

        function fetchProjects() {
            fetch('/projectsAPI')
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
                                    <h5 class="card-title">${project.naam}</h5>
                                    <p class="card-text">${project.beschrijving}</p>
                                    <p class="card-text"><strong>Startdatum:</strong> ${project.startdatum}</p>
                                    <p class="card-text"><strong>Einddatum:</strong> ${project.einddatum}</p>
                                    <p class="card-text"><strong>Status:</strong> ${project.status}</p>
                                    <button class="btn btn-primary btn-sm" onclick="editProject(${project.projectId})">Aanpassen</button>
                                    <button class="btn btn-secondary btn-sm" onclick="showPhases(${project.projectId})">Toon Fasen</button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteProject(${project.projectId})">Verwijderen</button>
                                </div>
                            </div>
                        `;
                        projectList.appendChild(projectCard);
                    });
                })
                .catch(error => console.error('Error fetching projects:', error));
        }

        // Toon modal om project aan te passen
        function editProject(projectId) {
            const project = projects.find(p => p.id === projectId);
            if (project) {
                document.getElementById('editProjectId').value = project.id;
                document.getElementById('editProjectName').value = project.name;
                document.getElementById('editProjectDescription').value = project.description;
                const editModal = new bootstrap.Modal(document.getElementById('editProjectModal'));
                editModal.show();
            }
        }

            // Function to delete a user
        function deleteProject(projectId) {
            fetch(`/projects/${projectId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                fetchUsers();  // Refresh the user list
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                document.getElementById('message').innerHTML = `<div class="alert alert-danger">Failed to delete user</div>`;
            });
        }

        // Toon modal met fasen
        function showPhases(projectId) {
            const phaseList = document.getElementById('phaseList');
            phaseList.innerHTML = '';
            if (phases[projectId]) {
                phases[projectId].forEach(phase => {
                    const phaseItem = document.createElement('li');
                    phaseItem.className = 'list-group-item';
                    phaseItem.innerHTML = `<strong>${phase.name}</strong>: ${phase.details}`;
                    phaseList.appendChild(phaseItem);
                });
            }
            const phaseModal = new bootstrap.Modal(document.getElementById('phaseModal'));
            phaseModal.show();
        }

        fetchProjects();
    </script>
</body>
</html>
