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

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin for edit/deletion
    if ($userRole !== 'admin' && (isset($inputData['id']) || isset($inputData['operation_id']))) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Select all operations
    $stmt = $pdo->prepare('SELECT * FROM operations_tasweeqiya');
    $stmt->execute();
    $operations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return operations
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($operations);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Insert new operation
    $stmt = $pdo->prepare('INSERT INTO operations_tasweeqiya (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return new operation
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin for edit
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Update operation
    $stmt = $pdo->prepare('UPDATE operations_tasweeqiya SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated operation
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin for deletion
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete operation
    $stmt = $pdo->prepare('DELETE FROM operations_tasweeqiya WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted operation
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
    exit;
}