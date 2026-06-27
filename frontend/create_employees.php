**create_employees.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900">Create Employee</h1>
    <form id="employee-form" class="mt-6 space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="department" class="block text-sm font-medium text-slate-900">Department</label>
            <select id="department" name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select Department</option>
                <option value="Sales">Sales</option>
                <option value="Marketing">Marketing</option>
                <option value="IT">IT</option>
            </select>
        </div>
        <div>
            <label for="role" class="block text-sm font-medium text-slate-900">Role</label>
            <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select Role</option>
                <option value="Manager">Manager</option>
                <option value="Employee">Employee</option>
            </select>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Employee</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#employee-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/employees.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_employees.php';
                    } else {
                        alert('Error creating employee');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**employees.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['department']) && isset($_POST['role'])) {
    // Prepare SQL query
    $sql = "INSERT INTO employees (name, email, phone, department, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['department'], $_POST['role']);
    // Execute query
    $stmt->execute();
    // Check if query is successful
    if ($stmt->affected_rows == 1) {
        echo 'success';
    } else {
        echo 'Error creating employee';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>