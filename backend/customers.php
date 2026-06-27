<?php
require_once 'db.php';

// Get the current user's role
$current_user_role = $_SESSION['role'];

// Get the request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($request_method === 'GET') {
    // Get the customer ID (if provided)
    $customer_id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow GET requests for all customers
    if ($current_user_role !== 'admin' && $customer_id === null) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query to select customers
    $stmt = $pdo->prepare('SELECT * FROM customers');
    if ($customer_id !== null) {
        $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Return the customers in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($customer !== null ? $customer : $customers);
    exit;
}

// Handle POST request
if ($request_method === 'POST') {
    // Get the customer data from the request body
    $customer_data = json_decode(file_get_contents('php://input'), true);

    // Validate the customer data
    if (!isset($customer_data['name']) || !isset($customer_data['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the customer data
    $customer_data['name'] = htmlspecialchars($customer_data['name']);
    $customer_data['email'] = filter_var($customer_data['email'], FILTER_VALIDATE_EMAIL);

    // Prepare the SQL query to insert a customer
    $stmt = $pdo->prepare('INSERT INTO customers (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $customer_data['name']);
    $stmt->bindParam(':email', $customer_data['email']);
    $stmt->execute();

    // Return the inserted customer in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($customer_data);
    exit;
}

// Handle PUT request
if ($request_method === 'PUT') {
    // Get the customer ID and data from the request body
    $customer_id = $_GET['id'];
    $customer_data = json_decode(file_get_contents('php://input'), true);

    // Validate the customer data
    if (!isset($customer_data['name']) || !isset($customer_data['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the customer data
    $customer_data['name'] = htmlspecialchars($customer_data['name']);
    $customer_data['email'] = filter_var($customer_data['email'], FILTER_VALIDATE_EMAIL);

    // Check if the user is an admin to allow PUT requests
    if ($current_user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query to update a customer
    $stmt = $pdo->prepare('UPDATE customers SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $customer_data['name']);
    $stmt->bindParam(':email', $customer_data['email']);
    $stmt->execute();

    // Return the updated customer in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($customer_data);
    exit;
}

// Handle DELETE request
if ($request_method === 'DELETE') {
    // Get the customer ID
    $customer_id = $_GET['id'];

    // Check if the user is an admin to allow DELETE requests
    if ($current_user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query to delete a customer
    $stmt = $pdo->prepare('DELETE FROM customers WHERE id = :id');
    $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();

    // Return a success message in JSON format
    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Customer deleted successfully'));
    exit;
}