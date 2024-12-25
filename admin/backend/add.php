<?php
header('Content-Type: application/json');
session_start();


if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['role'])) {
    echo json_encode(['error' => 'All fields are required']);
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit();
}

$username = trim($_POST['username']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = trim($_POST['role']);

// Fix the SQL query to include three parameters
$stmt = $conn->prepare("INSERT INTO admin (user_name, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => 'User added successfully']);
} else {
    echo json_encode(['error' => 'Failed to add user']);
}

$stmt->close();
$conn->close();
?>