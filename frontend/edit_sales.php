**edit_sales.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/sales.php?id=' . $id;
$existingRecord = json_decode(file_get_contents($url), true);

// Set form fields
$customer = $existingRecord['customer'];
$amount = $existingRecord['amount'];
$date = $existingRecord['date'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sales</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit Sales</h2>
        <form id="edit-sales-form">
            <div class="mb-4">
                <label for="customer" class="block text-slate-900 text-sm font-bold mb-2">Customer:</label>
                <input type="text" id="customer" name="customer" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $customer ?>">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-slate-900 text-sm font-bold mb-2">Amount:</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $amount ?>">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-slate-900 text-sm font-bold mb-2">Date:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $date ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-sales-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/sales.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_sales.php';
                        } else {
                            alert('Error updating sales record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**sales.php (backend)**

<?php
// Update existing sales record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $_GET['id'];
    $customer = $_POST['customer'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $sql = "UPDATE sales SET customer = '$customer', amount = '$amount', date = '$date' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>