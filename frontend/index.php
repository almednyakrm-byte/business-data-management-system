<?php
session_start();

// Check if user is authenticated
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
    <title>نظام إدارة الأعمال والبيانات التسويقية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex flex-col h-screen">
        <header class="bg-emerald-600 text-white py-4">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <h1 class="text-3xl font-bold">نظام إدارة الأعمال والبيانات التسويقية</h1>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </header>
        <main class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="glassmorphism-card w-full md:w-1/2 p-4">
                    <h2 class="text-2xl font-bold mb-4">مرحباً بكم</h2>
                    <p class="text-gray-600">هذا هو داشبورد النظام</p>
                </div>
                <div class="glassmorphism-card w-full md:w-1/2 p-4">
                    <h2 class="text-2xl font-bold mb-4">إحصائيات النظام</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h3 class="text-lg font-bold mb-2">عمليات تسويقية</h3>
                            <p class="text-gray-600" id="operations-count">...</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h3 class="text-lg font-bold mb-2">بيانات مالية</h3>
                            <p class="text-gray-600" id="financial-data-count">...</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h3 class="text-lg font-bold mb-2">بيانات تسويقية</h3>
                            <p class="text-gray-600" id="marketing-data-count">...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="glassmorphism-card w-full p-4 mt-4">
                <h2 class="text-2xl font-bold mb-4">روابط سريعة</h2>
                <ul class="list-none mb-0">
                    <li class="mb-2">
                        <a href="operations.php" class="text-gray-600 hover:text-gray-800">العمليات التسويقية</a>
                    </li>
                    <li class="mb-2">
                        <a href="financial-data.php" class="text-gray-600 hover:text-gray-800">البيانات المالية</a>
                    </li>
                    <li class="mb-2">
                        <a href="marketing-data.php" class="text-gray-600 hover:text-gray-800">البيانات التسويقية</a>
                    </li>
                </ul>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
        <script>
            axios.get('/api/stats')
                .then(response => {
                    document.getElementById('operations-count').textContent = response.data.operations_count;
                    document.getElementById('financial-data-count').textContent = response.data.financial_data_count;
                    document.getElementById('marketing-data-count').textContent = response.data.marketing_data_count;
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    </div>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. It also uses a glassmorphism card layout and fetches stats dynamically via a JavaScript API call from the backend files. The color palette used is emerald-600 and teal-500.

Please note that you need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, you need to create the backend files to handle the API calls and return the stats data.