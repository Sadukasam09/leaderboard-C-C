<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.html');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Connection failed']));
}

try {
    $result = $conn->query("SELECT id,school,email,phone,name FROM players");
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $players = [];
    while ($row = $result->fetch_assoc()) {
        $players[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'school' => $row['school'],
            'email' => $row['email'],
            'phone' => $row['phone']
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($players);
    
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>