<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();


    $topic_id = intval($_POST['topic_id']);
    $question_type = $_POST['question_type'];
    $question = trim($_POST['question']);
    $correct_answer = '';

    if ($question_type === 'mcq') {
        $option_a = trim($_POST['option_a']);
        $option_b = trim($_POST['option_b']);
        $option_c = trim($_POST['option_c']);
        $option_d = trim($_POST['option_d']);
        $correct_answer = $_POST['correct_answer'];
    } else {
        $option_a = $option_b = $option_c = $option_d = null;
        $correct_answer = trim($_POST['correct_answer_fill']);
    }

    $stmt = $conn->prepare("INSERT INTO quiz_questions 
        (topic_id, question_type, question, option_a, option_b, option_c, option_d, correct_answer) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $topic_id, $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Quiz question added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<a href="admin_dashboard.php">← Back to Dashboard</a>
