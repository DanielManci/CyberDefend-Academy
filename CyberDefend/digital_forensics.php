<?php
session_start();
require_once 'db.php';

// Topic ID for Digital Forensics
$topicId = 5;

// Pull topic content from DB
$stmt = $conn->prepare("SELECT title, short_description, description, video_link FROM topics WHERE id = ?");
$stmt->bind_param("i", $topicId);
$stmt->execute();
$result = $stmt->get_result();
$topic = $result->fetch_assoc();
$stmt->close();

if (!$topic) {
    die("Topic not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($topic['title']) ?></title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<main class="topics-main">
  <div class="main-title">
  <h2>Topic <?= $topicId ?>: <?= htmlspecialchars($topic['title']) ?></h2>
<?php if (!empty($topic['short_description'])): ?>
  <p class="subtitle"><?= htmlspecialchars($topic['short_description']) ?></p>
<?php endif; ?>

  </div>

  <?php if (!empty($topic['video_link'])): ?>
  <div class="video-section" style="text-align: center; margin-bottom: 40px;">
    <div class="video-preview" style="text-align: center; margin-bottom: 30px;">
      <a href="<?= htmlspecialchars($topic['video_link']) ?>" target="_blank">
        <img src="images/Digital_Forensics.png" style="width: 30%; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
      </a>
    </div>
  </div>
  <?php endif; ?>

  <section class="section-content" style="max-width: 900px; margin: 0 auto; text-align: left;">
    <?= $topic['description'] ?>
  </section>

  <div style="text-align: center; margin: 40px 0;">
    <a href="forensics_quiz.php" class="btn">Start Quiz</a>
  </div>
</main>

<footer class="footer">
  <a href="privacy.php">Privacy</a>
  <a href="terms.php">Terms</a>
  <a href="contact.php">Contact</a>
  <a href="about.php">About</a>
</footer>

</body>
</html>
