<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    csrf_check();
    http_response_code(405);
    exit('Method Not Allowed');
}

// Basic input validation
$id                = isset($_POST['topic_id']) ? (int) $_POST['topic_id'] : 0;
$title             = isset($_POST['title']) ? trim($_POST['title']) : '';
$short_description = isset($_POST['short_description']) ? trim($_POST['short_description']) : '';
$description       = isset($_POST['description']) ? trim($_POST['description']) : '';
$video_link        = isset($_POST['video_link']) ? trim($_POST['video_link']) : '';

if ($id <= 0 || $title === '' || $description === '') {
    http_response_code(400);
    exit('Missing or invalid fields.');
}

if (mb_strlen($short_description) > 255) {
    $short_description = mb_substr($short_description, 0, 255);
}

$sql = "UPDATE topics
        SET title = ?, short_description = ?, description = ?, video_link = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    exit('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('ssssi', $title, $short_description, $description, $video_link, $id);

if (!$stmt->execute()) {
    http_response_code(500);
    exit('Update failed: ' . $stmt->error);
}

$stmt->close();
$conn->close();

$topicId = (int)$_POST['topic_id'];
header("Location: admin_dashboard.php?update=topic&id={$topicId}&saved=1#update-topic");
exit;

