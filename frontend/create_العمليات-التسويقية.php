**create_العمليات-التسويقية.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $budget = trim($_POST['budget']);
    $status = trim($_POST['status']);

    // Insert data into database
    $sql = "INSERT INTO `العمليات_التسويقية` (`name`, `description`, `budget`, `status`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $description, $budget, $status);
    $stmt->execute();

    // Redirect back to list page
    header('Location: list_العمليات-التسويقية.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة عمليات تسويقية جديدة</title>
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
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إضافة عمليات تسويقية جديدة</h1>
        <form id="create-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم العملية التسويقية:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف العملية التسويقية:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <div class="mb-4">
                <label for="budget" class="block text-gray-700 text-sm font-bold mb-2">ميزانية العملية التسويقية:</label>
                <input type="number" id="budget" name="budget" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">حالة العملية التسويقية:</label>
                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="active">نشطة</option>
                    <option value="inactive">غير نشطة</option>
                </select>
            </div>
            <button type="submit" id="submit-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/العمليات-التسويقية.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data == 'success') {
                            window.location.href = 'list_العمليات-التسويقية.php';
                        } else {
                            alert('Error: ' + data);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/العمليات-التسويقية.php` with the actual URL of your backend script that handles the form submission. Also, update the database connection and query to match your actual database schema.