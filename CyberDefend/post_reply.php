<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['reply'])) {
    $post_id = intval($_POST['post_id']);
    $reply = trim($_POST['reply']);
    $username = $_SESSION['username'] ?? 'Anonymous';

    if (!empty($reply)) {
        $stmt = $conn->prepare("INSERT INTO forum_replies (post_id, username, reply) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $post_id, $username, $reply);
        $stmt->execute();
    }
}

header("Location: forum.php");
exit();
