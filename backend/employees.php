<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM employees');
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employees);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($input['role'], FILTER_SANITIZE_STRING);

    // Prepare insert statement
    $stmt = $pdo->prepare('INSERT INTO employees (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    // Execute insert statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Employee created successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create employee'));
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($input['role'], FILTER_SANITIZE_STRING);

    // Prepare update statement
    $stmt = $pdo->prepare('UPDATE employees SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);

    // Execute update statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee updated successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update employee'));
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare delete statement
    $stmt = $pdo->prepare('DELETE FROM employees WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute delete statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee deleted successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete employee'));
        exit;
    }
}