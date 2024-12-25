<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: ../web/leaderboardupdate.html'); // Redirect to leaderboard if logged in
    exit();
}

// Initialize error message
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT password FROM admin WHERE user_name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: ../web/leaderboardupdate.html'); // Redirect to leaderboard
            exit();
        }
    }

    // Display error message
    header('Location: ../web/login.html?error=1');
    exit();

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

?>