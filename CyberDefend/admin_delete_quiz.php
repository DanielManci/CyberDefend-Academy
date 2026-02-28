<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    csrf_check();
    $questionId = intval($_POST['question_id']);

    $stmt = $conn->prepare("DELETE FROM quiz_questions WHERE id = ?");
    $stmt->bind_param("i", $questionId);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Quiz question deleted successfully.</p>";
    } else {
        echo "<p style='color:red;'>Failed to delete quiz question: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

<a href="admin_dashboard.php">← Back to Dashboard</a>
