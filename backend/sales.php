<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    $limit = isset($inputData['limit']) ? intval($inputData['limit']) : 10;
    $offset = isset($inputData['offset']) ? intval($inputData['offset']) : 0;
    $filter = isset($inputData['filter']) ? $inputData['filter'] : '';

    // Check user role for admin-only access
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to retrieve sales data
    $sql = "SELECT * FROM sales WHERE 1=1";
    if ($filter) {
        $sql .= " AND (name LIKE :filter OR description LIKE :filter)";
    }
    $sql .= " ORDER BY id DESC LIMIT :limit OFFSET :offset";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':filter', $filter, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return sales data
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($salesData);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';
    $amount = isset($inputData['amount']) ? intval($inputData['amount']) : 0;

    // Check user role for admin-only access
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to insert new sale
    $sql = "INSERT INTO sales (name, description, amount, created_by) VALUES (:name, :description, :amount, :userID)";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return inserted sale data
    $saleID = $pdo->lastInsertId();
    $saleData = ['id' => $saleID, 'name' => $name, 'description' => $description, 'amount' => $amount];
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($saleData);
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate and sanitize input
    $saleID = isset($inputData['id']) ? intval($inputData['id']) : 0;
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';
    $amount = isset($inputData['amount']) ? intval($inputData['amount']) : 0;

    // Check user role for admin-only access
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to update sale
    $sql = "UPDATE sales SET name = :name, description = :description, amount = :amount WHERE id = :id AND created_by = :userID";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $stmt->bindParam(':id', $saleID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return updated sale data
    $saleData = ['id' => $saleID, 'name' => $name, 'description' => $description, 'amount' => $amount];
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($saleData);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate and sanitize input
    $saleID = isset($inputData['id']) ? intval($inputData['id']) : 0;

    // Check user role for admin-only access
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query to delete sale
    $sql = "DELETE FROM sales WHERE id = :id AND created_by = :userID";

    // Prepare and execute query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $saleID, PDO::PARAM_INT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Return success message
    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Sale deleted successfully']);
}