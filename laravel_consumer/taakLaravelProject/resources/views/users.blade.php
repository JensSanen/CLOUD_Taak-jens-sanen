<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flask Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="my-4">Flask Users</h1>

    <!-- Display Users -->
    <h2>All Users</h2>
    <table class="table table-striped" id="usersTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Add New User -->
    <h2>Add New User</h2>
    <form id="createUserForm">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>

    <!-- Error or Success Messages -->
    <div id="message" class="mt-4"></div>
</div>

<script>
    // Function to fetch users and populate the table
    function fetchUsers() {
        // Hier doen we dus een oproep naar het lokale endpoint
        fetch('/flaskusers')
            .then(response => response.json())
            .then(users => {
                const usersTable = document.getElementById('usersTable').getElementsByTagName('tbody')[0];
                usersTable.innerHTML = '';  // Clear existing rows

                users.forEach(user => {
                    const row = usersTable.insertRow();
                    row.insertCell(0).textContent = user.id;
                    row.insertCell(1).textContent = user.name;
                    row.insertCell(2).textContent = user.email;

                    const actionsCell = row.insertCell(3);
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.className = 'btn btn-danger btn-sm';
                    deleteButton.onclick = () => deleteUser(user.id);
                    actionsCell.appendChild(deleteButton);
                });
            })
            .catch(error => console.error('Error fetching users:', error));
    }

    // Function to delete a user
    function deleteUser(id) {
        fetch(`/users/${id}`, {
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

    // Handle form submission for creating a new user
    document.getElementById('createUserForm').addEventListener('submit', function (event) {
        event.preventDefault();

        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
        };

        fetch('/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('message').innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            document.getElementById('createUserForm').reset();  // Clear form
            fetchUsers();  // Refresh the user list
        })
        .catch(error => {
            console.error('Error creating user:', error);
            document.getElementById('message').innerHTML = `<div class="alert alert-danger">Failed to create user</div>`;
        });
    });

    // Fetch users on page load
    fetchUsers();
</script>
</body>
</html>
