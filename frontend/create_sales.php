**create_sales.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_sales_form.php';

// Include footer
include 'footer.php';


**create_sales_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New Sales Record</h2>
    <form id="create-sales-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="customer_name" class="block text-sm font-medium text-slate-900">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter customer name">
            </div>
            <div>
                <label for="product_name" class="block text-sm font-medium text-slate-900">Product Name:</label>
                <input type="text" id="product_name" name="product_name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter product name">
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter quantity">
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
                <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-slate-300 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter price">
            </div>
        </div>
        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Sales Record</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-sales-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/sales.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_sales.php';
                    } else {
                        alert('Error creating sales record');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sales Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navigation.php'; ?>
    <div class="container mx-auto p-4">
        <?php include 'create_sales.php'; ?>
    </div>
</body>
</html>


**footer.php**

</div>
</body>
</html>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto p-4 flex justify-between items-center">
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Home</a>
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Dashboard</a>
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Sales</a>
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Customers</a>
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Products</a>
        <a href="#" class="text-sm font-medium text-white hover:text-indigo-500">Logout</a>
    </div>
</nav>


Note: This code assumes that you have jQuery and Tailwind CSS installed in your project. Also, make sure to replace '../backend/sales.php' with the actual URL of your backend script.