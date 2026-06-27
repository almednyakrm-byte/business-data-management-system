**create_البيانات-المالية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $amount = trim($_POST['amount']);
    $date = trim($_POST['date']);

    if (!empty($name) && !empty($description) && !empty($amount) && !empty($date)) {
        // Insert data into database
        $sql = "INSERT INTO البيانات_المالية (name, description, amount, date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $amount, $date);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_البيانات-المالية.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New بيانات المالية Record</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
        </div>
        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
            <input type="number" id="amount" name="amount" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Create Record</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-600 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/البيانات-المالية.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_البيانات-المالية.php';
                    } else {
                        alert('Error creating record');
                    }
                }
            });
        });
    });
</script>

**backend/البيانات-المالية.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $amount = trim($_POST['amount']);
    $date = trim($_POST['date']);

    // Insert data into database
    $sql = "INSERT INTO البيانات_المالية (name, description, amount, date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $description, $amount, $date);
    $stmt->execute();

    // Return success message
    echo 'success';
}