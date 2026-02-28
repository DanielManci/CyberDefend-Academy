<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Not logged in";
    exit();
}

$user_id = $_SESSION['user_id'];
$topic_id = (int)$_POST['topic_id'];
$score = (int)$_POST['score'];
$type = $_POST['type']; // "quiz" or "test"

// Check if a row exists
$stmt = $conn->prepare("SELECT id FROM user_scores WHERE user_id = ? AND topic_id = ?");
$stmt->bind_param("ii", $user_id, $topic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    if ($type === 'quiz') {
        $stmt = $conn->prepare("UPDATE user_scores SET quiz_score = ? WHERE user_id = ? AND topic_id = ?");
        $stmt->bind_param("iii", $score, $user_id, $topic_id);
    } elseif ($type === 'test') {
        $stmt = $conn->prepare("UPDATE user_scores SET test_score = ? WHERE user_id = ? AND topic_id = ?");
        $stmt->bind_param("iii", $score, $user_id, $topic_id);
    }
    $stmt->execute();
} else {
    // Insert new row
    $quiz_score = ($type === 'quiz') ? $score : 0;
    $test_score = ($type === 'test') ? $score : 0;
    $stmt = $conn->prepare("INSERT INTO user_scores (user_id, topic_id, quiz_score, test_score) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $user_id, $topic_id, $quiz_score, $test_score);
    $stmt->execute();
}

echo "Score saved successfully.";
