<?php
header('Content-Type: application/json');
session_start();

try {
    // Validate inputs
    $required_fields = ['name', 'school', 'email', 'phone'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("$field is required");
        }
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $school = filter_var($_POST['school'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);

    $randomid = rand(100000, 999999);

    // Check if email exists
    $check = $conn->prepare("SELECT id FROM players WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        throw new Exception("Email already registered");
    }
    $check->close();

    // Insert player
    $stmt = $conn->prepare("INSERT INTO players (id,name, school, email, phone) VALUES (?,?, ?, ?, ?)");
    $stmt->bind_param("sssss",$randomid, $name, $school, $email, $phone);
    
    if (!$stmt->execute()) {
        throw new Exception("Registration failed: " . $stmt->error);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Player registered successfully!',
        'playerId' => $conn->insert_id
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>