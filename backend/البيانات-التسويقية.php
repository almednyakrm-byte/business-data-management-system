<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/data-marketing' => array(
        'GET' => 'getData',
        'POST' => 'createData',
    ),
    '/data-marketing/:id' => array(
        'GET' => 'getDataById',
        'PUT' => 'updateData',
        'DELETE' => 'deleteData',
    ),
);

// Define function to get data
function getData() {
    global $db;
    try {
        $stmt = $db->prepare('SELECT * FROM data_marketing');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define function to create data
function createData() {
    global $db;
    try {
        // Validate input data
        if (!isset($inputData['name']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            return;
        }
        
        // Sanitize input data
        $name = $db->quote($inputData['name']);
        $description = $db->quote($inputData['description']);
        
        // Insert data into database
        $stmt = $db->prepare('INSERT INTO data_marketing (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return created data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Data created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define function to get data by id
function getDataById($id) {
    global $db;
    try {
        $stmt = $db->prepare('SELECT * FROM data_marketing WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Data not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define function to update data
function updateData($id) {
    global $db;
    try {
        // Validate input data
        if (!isset($inputData['name']) || !isset($inputData['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            return;
        }
        
        // Sanitize input data
        $name = $db->quote($inputData['name']);
        $description = $db->quote($inputData['description']);
        
        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            return;
        }
        
        // Update data in database
        $stmt = $db->prepare('UPDATE data_marketing SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return updated data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Data updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Define function to delete data
function deleteData($id) {
    global $db;
    try {
        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            return;
        }
        
        // Delete data from database
        $stmt = $db->prepare('DELETE FROM data_marketing WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return deleted data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Data deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route);
array_shift($route);
$route = implode('/', $route);

// Check if route exists
if (isset($routes[$route])) {
    $method = $_SERVER['REQUEST_METHOD'];
    if (isset($routes[$route][$method])) {
        $func = $routes[$route][$method];
        if ($method == 'GET') {
            $func();
        } elseif ($method == 'POST') {
            createData();
        } elseif ($method == 'PUT') {
            $id = explode('/', $_SERVER['REQUEST_URI']);
            array_shift($id);
            array_shift($id);
            $id = end($id);
            updateData($id);
        } elseif ($method == 'DELETE') {
            $id = explode('/', $_SERVER['REQUEST_URI']);
            array_shift($id);
            array_shift($id);
            $id = end($id);
            deleteData($id);
        }
    } else {
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
    }
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
}