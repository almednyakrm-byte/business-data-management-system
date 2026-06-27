**edit_البيانات-التسويقية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/البيانات-التسويقية.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات التسويقية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل البيانات التسويقية</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">الاسم:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف:</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-md">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/البيانات-التسويقية.php',
                    data: formData,
                    success: function() {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/البيانات-التسويقية.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'name' => 'اسم المورد',
    'email' => 'example@example.com',
    'phone' => '0123456789'
);

// Output JSON
echo json_encode($existingRecord);