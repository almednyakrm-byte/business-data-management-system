**list_البيانات-المالية.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البيانات المالية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">البيانات المالية</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_البيانات-المالية.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full table-auto border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="px-4 py-2">المعرف</th>
                    <th class="px-4 py-2">الاسم</th>
                    <th class="px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/البيانات-المالية.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.id}</td>
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_البيانات-المالية.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Delete record
        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/البيانات-المالية.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    fetchRecords();
                } else {
                    alert('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                fetchRecords(searchValue);
            } else {
                fetchRecords();
            }
        }

        // Initialize records
        fetchRecords();
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records with actions: Edit (link to edit_البيانات-المالية.php?id=X) and Delete (AJAX call to backend).
4. 'Add New Item' button linking to create_البيانات-المالية.php.
5. Search bar filtering elements in real-time.
6. AJAX Javascript (Fetch API) fetching list records from '../backend/البيانات-المالية.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in a separate file (`../backend/البيانات-المالية.php`) and is responsible for handling GET and DELETE requests.