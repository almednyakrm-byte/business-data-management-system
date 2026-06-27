**edit_البيانات-المالية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/البيانات-المالية.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات المالية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .bg-emerald-600 {
            background-color: #0d6efd;
        }
        .text-teal-500 {
            color: #0fc2c9;
        }
    </style>
</head>
<body class="bg-emerald-600 text-teal-500">
    <div class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">تعديل البيانات المالية</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-lg font-bold mb-2">اسم المالية:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-400 rounded" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-lg font-bold mb-2">وصف المالية:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-400 rounded"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
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
                    url: '../backend/البيانات-المالية.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/البيانات-المالية.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'id' => $id,
    'name' => 'اسم المالية',
    'description' => 'وصف المالية'
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($data);