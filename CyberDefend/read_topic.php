<?php
require_once 'db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "No topic selected.";
    exit;
}

$topicId = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->bind_param("i", $topicId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Topic not found.";
    exit;
}

$topic = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($topic['title']) ?> - Topic</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="topic-reader">
  <h1><?= htmlspecialchars($topic['title']) ?></h1>
  <div class="topic-full-content">
    <p><?= nl2br(htmlspecialchars($topic['description'])) ?></p>
  </div>

  <?php if (!empty($topic['video_link'])): ?>
    <p><a href="<?= htmlspecialchars($topic['video_link']) ?>" target="_blank"><button>Watch Video</button></a></p>
  <?php endif; ?>

  <a href="topics.php"><button>← Back to Topics</button></a>
</body>
</html>
