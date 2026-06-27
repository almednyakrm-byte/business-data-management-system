<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'البيانات-التسويقية';

// Page title
$page_title = 'إضافة البيانات التسويقية';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl text-emerald-600 mb-4"><?php echo $page_title; ?></h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">اسم البيانات التسويقية</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-gray-600 mb-2">وصف البيانات التسويقية</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
            </div>
            <div class="mb-4">
                <label for="target_audience" class="block text-sm text-gray-600 mb-2">الجمهور المستهدف</label>
                <input type="text" id="target_audience" name="target_audience" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="marketing_channels" class="block text-sm text-gray-600 mb-2">قنوات التسويق</label>
                <input type="text" id="marketing_channels" name="marketing_channels" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="budget" class="block text-sm text-gray-600 mb-2">الميزانية</label>
                <input type="number" id="budget" name="budget" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-teal-500 focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/<?php echo $mod_slug; ?>.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>