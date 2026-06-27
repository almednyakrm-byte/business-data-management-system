**edit_employees.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get employee ID from URL
$id = $_GET['id'];

// Fetch employee data from backend
$data = json_decode(file_get_contents('../backend/employees.php?id=' . $id), true);

// Check if employee data exists
if (empty($data)) {
    echo 'Employee not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Employee';
$mod_slug = 'employees';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
        <form id="employee-form" class="bg-white p-4 rounded shadow-md">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch employee data via GET
            $.ajax({
                type: 'GET',
                url: '../backend/employees.php?id=<?= $id ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            // Submit form via AJAX PUT
            $('#employee-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/employees.php',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_<?= $mod_slug ?>.php';
                        } else {
                            alert('Error updating employee.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**employees.php (backend)**

<?php
// Check if employee ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Employee ID not set.'));
    exit;
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Fetch employee data
$stmt = $conn->prepare('SELECT * FROM employees WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Close database connection
$conn = null;

// Output employee data as JSON
echo json_encode($data);
?>


**employees.php (backend) - PUT request handler**

<?php
// Check if employee ID and data are set
if (!isset($_GET['id']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['phone'])) {
    echo json_encode(array('error' => 'Invalid request.'));
    exit;
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Update employee data
$stmt = $conn->prepare('UPDATE employees SET name = :name, email = :email, phone = :phone WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':email', $_POST['email']);
$stmt->bindParam(':phone', $_POST['phone']);
$stmt->execute();

// Check if update was successful
if ($stmt->rowCount() > 0) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('error' => 'Error updating employee.'));
}

// Close database connection
$conn = null;
?>