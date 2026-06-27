**list_customers.php**

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
    <title>Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-slate-900 py-2">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-indigo-500">Hello, <?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
                </div>
            </div>
        </nav>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl text-indigo-500">Customers</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_customers.php'">Add New Item</button>
            <div class="bg-white p-4 rounded shadow-md">
                <form class="flex items-center mb-4">
                    <input type="search" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Search..." id="search" name="search">
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r-lg" onclick="searchCustomers()">Search</button>
                </form>
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="py-3 px-6">ID</th>
                            <th scope="col" class="py-3 px-6">Name</th>
                            <th scope="col" class="py-3 px-6">Email</th>
                            <th scope="col" class="py-3 px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch customers list from backend
                        $customers = json_decode(file_get_contents('../backend/customers.php'), true);
                        foreach ($customers as $customer) {
                            ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="py-4 px-6"><?= $customer['id'] ?></td>
                                <td class="py-4 px-6"><?= $customer['name'] ?></td>
                                <td class="py-4 px-6"><?= $customer['email'] ?></td>
                                <td class="py-4 px-6">
                                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_customers.php?id=<?= $customer['id'] ?>'">Edit</button>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteCustomer(<?= $customer['id'] ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function searchCustomers() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetch('../backend/customers.php?search=' + searchValue)
                    .then(response => response.json())
                    .then(customers => {
                        const tableBody = document.querySelector('tbody');
                        tableBody.innerHTML = '';
                        customers.forEach(customer => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_customers.php?id=${customer.id}'">Edit</button>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteCustomer(${customer.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/customers.php')
                    .then(response => response.json())
                    .then(customers => {
                        const tableBody = document.querySelector('tbody');
                        tableBody.innerHTML = '';
                        customers.forEach(customer => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_customers.php?id=${customer.id}'">Edit</button>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteCustomer(${customer.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }
        }

        function deleteCustomer(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch('../backend/customers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Customer deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting customer!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, table displaying customer records, and a search bar. It also includes AJAX functionality to fetch customer records from the backend and delete customers using a DELETE request.