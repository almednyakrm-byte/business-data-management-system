<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البيانات التسويقية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-lg font-bold text-teal-500 hover:text-teal-700">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">البيانات التسويقية</h1>
        <div class="flex justify-between mb-4">
            <a href="create_البيانات-التسويقية.php" class="bg-emerald-600 text-white p-2 rounded hover:bg-emerald-700">إضافة جديد</a>
            <input type="search" id="search" class="p-2 rounded" placeholder="بحث...">
        </div>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-teal-500 text-white">
                <tr>
                    <th class="p-2 border border-gray-300">الاسم</th>
                    <th class="p-2 border border-gray-300">الوصف</th>
                    <th class="p-2 border border-gray-300">العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table content will be generated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get list records
        fetch('../backend/البيانات-التسويقية.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="p-2 border border-gray-300">${item.name}</td>
                        <td class="p-2 border border-gray-300">${item.description}</td>
                        <td class="p-2 border border-gray-300">
                            <a href="edit_البيانات-التسويقية.php?id=${item.id}" class="text-emerald-600 hover:text-emerald-700">تعديل</a>
                            <button class="text-teal-500 hover:text-teal-700" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete item using AJAX
        function deleteItem(id) {
            fetch('../backend/البيانات-التسويقية.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const rows = document.querySelectorAll('#table-body tr');
                    rows.forEach(row => {
                        const idCell = row.querySelector('td:nth-child(3) button');
                        if (idCell && idCell.onclick.toString().includes(`deleteItem(${id})`)) {
                            row.remove();
                        }
                    });
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#table-body tr');
            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(1)');
                const descriptionCell = row.querySelector('td:nth-child(2)');
                if (nameCell.textContent.toLowerCase().includes(searchValue) || descriptionCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>