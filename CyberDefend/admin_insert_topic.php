<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $video_link = trim($_POST['video_link']);

    $sql = "INSERT INTO topics (title, short_description, description, video_link) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['title'], $_POST['short_description'], $_POST['description'], $_POST['video_link']);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Topic added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
<a href="admin_dashboard.php">← Back to Dashboard</a>
