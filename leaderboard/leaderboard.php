<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
session_start();

try {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'ColorsAndCoders');
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get top 10 players
    $query = "SELECT name, score FROM players ORDER BY score DESC LIMIT 10";
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception($conn->error);
    }
    
    $leaderboard = [];
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = [
            'name' => $row['name'],
            'score' => (int)$row['score']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $leaderboard
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>