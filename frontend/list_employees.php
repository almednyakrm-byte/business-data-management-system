**list_employees.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header a {
            color: #fff;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .search-bar button {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header flex justify-between items-center p-4">
        <a href="index.php" class="text-lg font-bold">Dashboard</a>
        <div class="flex items-center">
            <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Employees List</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_employees.php'">Add New Item</button>
        </div>
        <div class="search-bar flex justify-between items-center mb-4">
            <input type="search" id="search-input" placeholder="Search employees...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchEmployees()">Search</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employees-list">
                <?php
                // Fetch employees list from backend
                $employees = json_decode(file_get_contents('../backend/employees.php'), true);
                foreach ($employees as $employee) {
                    ?>
                    <tr>
                        <td><?= $employee['id'] ?></td>
                        <td><?= $employee['name'] ?></td>
                        <td><?= $employee['email'] ?></td>
                        <td>
                            <a href="edit_employees.php?id=<?= $employee['id'] ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteEmployee(<?= $employee['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchEmployees() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/employees.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const employeesList = document.getElementById('employees-list');
                    employeesList.innerHTML = '';
                    data.forEach(employee => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${employee.id}</td>
                            <td>${employee.name}</td>
                            <td>${employee.email}</td>
                            <td>
                                <a href="edit_employees.php?id=${employee.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteEmployee(${employee.id})">Delete</button>
                            </td>
                        `;
                        employeesList.appendChild(row);
                    });
                });
        }

        function deleteEmployee(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                fetch('../backend/employees.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Employee deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting employee!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete employees.