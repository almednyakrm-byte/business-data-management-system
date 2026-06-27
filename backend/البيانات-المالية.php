<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all financial data
    $stmt = $pdo->prepare('SELECT * FROM البيانات_المالية');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return financial data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($inputData['name']);
    $amount = $pdo->quote($inputData['amount']);

    // Insert new financial data
    $stmt = $pdo->prepare('INSERT INTO البيانات_المالية (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return inserted financial data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Financial data inserted successfully'));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = $inputData['id'];
    $name = $pdo->quote($inputData['name']);
    $amount = $pdo->quote($inputData['amount']);

    // Update financial data
    $stmt = $pdo->prepare('UPDATE البيانات_المالية SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return updated financial data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Financial data updated successfully'));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = $inputData['id'];

    // Delete financial data
    $stmt = $pdo->prepare('DELETE FROM البيانات_المالية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted financial data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Financial data deleted successfully'));
    exit;
}