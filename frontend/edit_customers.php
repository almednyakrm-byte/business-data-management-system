**edit_customers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Fetch customer details via AJAX
$customerDetails = json_decode(file_get_contents('../backend/customers.php?id=' . $id), true);

// Check if customer exists
if (empty($customerDetails)) {
    echo 'Customer not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Customer</h2>
        <form id="editCustomerForm">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $customerDetails['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $customerDetails['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $customerDetails['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editCustomerForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/customers.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_customers.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**customers.php (backend)**

<?php
// Check if customer ID is provided
if (isset($_GET['id'])) {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch customer details
    $stmt = $db->prepare('SELECT * FROM customers WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $customerDetails = $stmt->fetch();

    // Return customer details as JSON
    echo json_encode($customerDetails);
} else {
    // Return error message
    echo 'Customer ID not provided';
}
?>


**customers.php (backend) - Update customer**

<?php
// Check if customer ID and data are provided
if (isset($_GET['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])) {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update customer details
    $stmt = $db->prepare('UPDATE customers SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->execute();

    // Return success message
    echo 'Customer updated successfully';
} else {
    // Return error message
    echo 'Invalid request';
}
?>