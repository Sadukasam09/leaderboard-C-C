<?php
header('Content-Type: application/json');
session_start();

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Handle POST request for score update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $newScore = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_INT);

    if (!$playerId || !$newScore) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit();
    }

    try {
        // Get current score
        $stmt = $conn->prepare("SELECT score FROM players WHERE id = ?");
        $stmt->bind_param("i", $playerId);
        $stmt->execute();
        $stmt->bind_result($currentScore);
        $stmt->fetch();
        $stmt->close();

        // Update score
        $updatedScore = $currentScore + $newScore;
        $stmt = $conn->prepare("UPDATE players SET score = ? WHERE id = ?");
        $stmt->bind_param("ii", $updatedScore, $playerId);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Update failed']);
        exit();
    }
}

// Get leaderboard data
try {
    $result = $conn->query("SELECT * FROM players ORDER BY score DESC LIMIT 10");
    $leaderboard = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($leaderboard);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch leaderboard']);
}

$conn->close();
?>