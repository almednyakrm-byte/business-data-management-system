**list_العمليات-التسويقية.php**

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
    <title>العمليات التسويقية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #03C75A;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body>
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">العمليات التسويقية</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_العمليات-التسويقية.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-4 py-2">العنوان</th>
                    <th class="border border-gray-400 px-4 py-2">التاريخ</th>
                    <th class="border border-gray-400 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        fetch('../backend/العمليات-التسويقية.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 px-4 py-2">${record.عنوان}</td>
                        <td class="border border-gray-400 px-4 py-2">${record.تاريخ}</td>
                        <td class="border border-gray-400 px-4 py-2">
                            <a href="edit_العمليات-التسويقية.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/العمليات-التسويقية.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${record.عنوان}</td>
                                <td class="border border-gray-400 px-4 py-2">${record.تاريخ}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_العمليات-التسويقية.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/العمليات-التسويقية.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 px-4 py-2">${record.عنوان}</td>
                                <td class="border border-gray-400 px-4 py-2">${record.تاريخ}</td>
                                <td class="border border-gray-400 px-4 py-2">
                                    <a href="edit_العمليات-التسويقية.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/العمليات-التسويقية.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is already implemented and returns the list of records in JSON format. The `deleteRecord` function sends a DELETE request to the backend API to delete the record with the specified ID.