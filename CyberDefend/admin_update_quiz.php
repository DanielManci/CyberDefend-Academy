<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = intval($_POST['question_id']);
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

    $stmt = $conn->prepare("UPDATE quiz_questions 
        SET question_type = ?, question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?
        WHERE id = ?");
    $stmt->bind_param("sssssssi", $question_type, $question, $option_a, $option_b, $option_c, $option_d, $correct_answer, $id);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Quiz question updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<a href="admin_dashboard.php">← Back to Dashboard</a>
