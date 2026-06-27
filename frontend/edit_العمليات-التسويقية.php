**edit_العمليات-التسويقية.php**

<?php
// Session validation
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$record = json_decode(file_get_contents('../backend/العمليات-التسويقية.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل العمليات التسويقية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">تعديل العمليات التسويقية</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $record['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">الوصف</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $record['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">الحالة</label>
                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="active" <?= $record['status'] == 'active' ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= $record['status'] == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/العمليات-التسويقية.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_العمليات-التسويقية.php';
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/العمليات-التسويقية.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Fetch existing record details
$record = array(
    'id' => $_GET['id'],
    'title' => 'العنوان',
    'description' => 'الوصف',
    'status' => 'active'
);

// Return JSON response
echo json_encode($record);