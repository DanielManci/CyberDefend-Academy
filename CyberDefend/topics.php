<?php require_once __DIR__ . '/session_boot.php'; ?>


<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'] ?? null;
$progress = [];

if ($userId) {
    $stmt = $conn->prepare("SELECT topic, score, total_questions FROM user_test_progress WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $progress[$row['topic']] = $row['score'] . '/' . $row['total_questions'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cybersecurity Topics</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="topics-page">

<?php include 'header.php'; ?>


<div class="topics-header">
  <h1>Cybersecurity Topics</h1>
  <p>Browse topics and start learning with video tutorials and practice questions</p>
</div>

<div class="topics-container">

  <!-- Original 5 Hardcoded Topics -->
  <div class="topic-row">
    <div class="topic-card blue">
      <h3>Network Security</h3>
      <a href="network_security.php"><button>Start Reading</button></a>
      <a href="https://drive.google.com/file/d/1sltCVFEc2FfaMTMnbH3UDfQ81B6k2Igr/view" target="_blank"><button>Watch Video</button></a>
      <a href="network_test.php"><button>Start Test</button></a>
      <div class="progress">Progress: <?= $progress['Network Security'] ?? 'Not taken yet' ?></div>
    </div>

    <div class="topic-card green">
      <h3>Ethical Hacking</h3>
      <a href="ethical_hacking.php"><button>Start Reading</button></a>
      <a href="https://drive.google.com/file/d/1uT6FLV3l327gaW5o-pknj-WdfzVWIZsh/view" target="_blank"><button>Watch Video</button></a>
      <a href="ethical_test.php"><button>Start Test</button></a>
      <div class="progress">Progress: <?= $progress['Ethical Hacking'] ?? 'Not taken yet' ?></div>
    </div>
  </div>

  <div class="topic-row">
    <div class="topic-card purple">
      <h3>Cryptography</h3>
      <a href="crypto.php"><button>Start Reading</button></a>
      <a href="https://drive.google.com/file/d/1myatfCRmpE0tVnpSfyVA2AJmMLFeNgbY/view" target="_blank"><button>Watch Video</button></a>
      <a href="crypto_test.php"><button>Start Test</button></a>
      <div class="progress">Progress: <?= $progress['Cryptography'] ?? 'Not taken yet' ?></div>
    </div>

    <div class="topic-card orange">
      <h3>Threat Analysis</h3>
      <a href="threat_analysis.php"><button>Start Reading</button></a>
      <a href="https://drive.google.com/file/d/1vsfO5UyHNCibSdd6zHoY5zHJFzUVQnM0/view" target="_blank"><button>Watch Video</button></a>
      <a href="threat_test.php"><button>Start Test</button></a>
      <div class="progress">Progress: <?= $progress['Threat Analysis'] ?? 'Not taken yet' ?></div>
    </div>
  </div>

  <div class="topic-row center-row">
    <div class="topic-card teal wide">
      <h3>Digital Forensics</h3>
      <a href="digital_forensics.php"><button>Start Reading</button></a>
      <a href="https://drive.google.com/file/d/1abM5l4InBDOXx7gkdcYbVI1cygiLKgoM/view" target="_blank"><button>Watch Video</button></a>
      <a href="forensics_test.php"><button>Start Test</button></a>
      <div class="progress">Progress: <?= $progress['Digital Forensics'] ?? 'Not taken yet' ?></div>
    </div>
  </div>

  <!-- Dynamic Topics (from DB, excluding the 5 main ones) -->
  <?php
$query = "SELECT * FROM topics ORDER BY id DESC";
$result = $conn->query($query);
$featured = ['Network Security', 'Ethical Hacking', 'Cryptography', 'Threat Analysis', 'Digital Forensics'];
?>

<?php while ($row = $result->fetch_assoc()): ?>
  <?php if (!in_array($row['title'], $featured)): ?>
    <div class="topic-row">
      <div class="topic-card gray">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars(substr($row['description'], 0, 150))) ?>...</p>

        <!-- Start Reading -->
        <a href="read_topic.php?id=<?= $row['id'] ?>"><button>Start Reading</button></a>

        <!-- Watch Video -->
        <?php if (!empty($row['video_link'])): ?>
          <a href="<?= htmlspecialchars($row['video_link']) ?>" target="_blank"><button>Watch Video</button></a>
        <?php endif; ?>

        <!-- Start Test -->
        <a href="generic_test.php?topic=<?= urlencode($row['title']) ?>"><button>Start Test</button></a>

        <!-- Progress Display -->
        <div class="progress">Progress: <?= $progress[$row['title']] ?? 'Not taken yet' ?></div>
      </div>
    </div>
  <?php endif; ?>
<?php endwhile; ?>


</div>

<footer class="footer">
  <a href="privacy.php">Privacy</a>
  <a href="terms.php">Terms</a>
  <a href="contact.php">Contact</a>
  <a href="about.php">About</a>
</footer>

</body>
</html>
