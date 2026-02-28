<?php
session_start();
if (!isset($_GET['topic'])) {
    echo "No topic selected.";
    exit;
}

$topic = htmlspecialchars($_GET['topic']);
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= $topic ?> - Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="topic-test">
  <h1><?= $topic ?> - Test Page</h1>
  <p>This is a placeholder for the test on <strong><?= $topic ?></strong>.</p>

  <a href="topics.php"><button>← Back to Topics</button></a>
</body>
</html>
