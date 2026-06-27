**list_sales.php**

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
    <title>Sales Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .table tr:nth-child(even) {
            background-color: #f7f7f7;
        }
        .table tr:hover {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            width: 50%;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Management</h1>
        <a href="index.php">Back to Index</a>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2>Sales List</h2>
            <a href="create_sales.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" placeholder="Search..." class="search-bar">
            <button id="search-btn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sales-list">
                <!-- List of sales records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Get search input and button elements
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');

        // Add event listener to search button
        searchBtn.addEventListener('click', () => {
            // Get search query
            const query = searchInput.value.trim();

            // Fetch sales list with search query
            fetch('../backend/sales.php?search=' + query)
                .then(response => response.json())
                .then(data => {
                    // Display sales list
                    const salesList = document.getElementById('sales-list');
                    salesList.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.price}</td>
                            <td>
                                <a href="edit_sales.php?id=${item.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteSales(${item.id})">Delete</button>
                            </td>
                        `;
                        salesList.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        });

        // Function to delete sales record
        function deleteSales(id) {
            // Confirm deletion
            if (confirm('Are you sure you want to delete this sales record?')) {
                // Send DELETE request to backend
                fetch('../backend/sales.php?id=' + id, { method: 'DELETE' })
                    .then(response => response.json())
                    .then(data => {
                        // Refresh sales list
                        fetch('../backend/sales.php')
                            .then(response => response.json())
                            .then(data => {
                                const salesList = document.getElementById('sales-list');
                                salesList.innerHTML = '';
                                data.forEach(item => {
                                    const row = document.createElement('tr');
                                    row.innerHTML = `
                                        <td>${item.id}</td>
                                        <td>${item.name}</td>
                                        <td>${item.quantity}</td>
                                        <td>${item.price}</td>
                                        <td>
                                            <a href="edit_sales.php?id=${item.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteSales(${item.id})">Delete</button>
                                        </td>
                                    `;
                                    salesList.appendChild(row);
                                });
                            })
                            .catch(error => console.error(error));
                    })
                    .catch(error => console.error(error));
            }
        }

        // Fetch sales list on page load
        fetch('../backend/sales.php')
            .then(response => response.json())
            .then(data => {
                // Display sales list
                const salesList = document.getElementById('sales-list');
                salesList.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>
                            <a href="edit_sales.php?id=${item.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteSales(${item.id})">Delete</button>
                        </td>
                    `;
                    salesList.appendChild(row);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>

Note: This code assumes that you have a `sales.php` file in the `../backend` directory that handles GET and DELETE requests for the sales list. You will need to create this file and implement the necessary logic to retrieve and delete sales records.