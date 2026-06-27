<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their user data
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    echo json_encode($user_data);
    exit;
}

// Handle the login action
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        // Check if the user exists and the password is correct
        if ($user_data && password_verify($password, $user_data['password'])) {
            // If the user exists and the password is correct, log them in
            $_SESSION['user_id'] = $user_data['id'];
            echo json_encode(['message' => 'Login successful']);
        } else {
            // If the user does not exist or the password is incorrect, return an error message
            echo json_encode(['message' => 'Invalid username or password']);
        }
    } else {
        // If the username or password is not set, return an error message
        echo json_encode(['message' => 'Username and password are required']);
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // If the username or email is already taken, return an error message
            echo json_encode(['message' => 'Username or email is already taken']);
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            // Log the user in
            $_SESSION['user_id'] = $mysqli->insert_id;
            echo json_encode(['message' => 'Registration successful']);
        }
    } else {
        // If the username, email, or password is not set, return an error message
        echo json_encode(['message' => 'Username, email, and password are required']);
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Log the user out
    session_destroy();
    echo json_encode(['message' => 'Logout successful']);
}

// Close the database connection
$mysqli->close();
?>