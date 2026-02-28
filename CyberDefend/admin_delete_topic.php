<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic_id'])) {
    csrf_check();
    $topicId = intval($_POST['topic_id']);

    $stmt = $conn->prepare("DELETE FROM topics WHERE id = ?");
    $stmt->bind_param("i", $topicId);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Topic deleted successfully.</p>";
    } else {
        echo "<p style='color:red;'>Failed to delete topic: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

<a href="admin_dashboard.php">← Back to Dashboard</a>
