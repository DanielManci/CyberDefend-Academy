<?php require_once __DIR__ . '/session_boot.php'; ?>


<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'User';
$userId = $_SESSION['user_id'];

// Leaderboard query (quiz_score + test_score only, no duplicates)
// Leaderboard query (quiz_score + latest test_score per topic)
$sql = "
  SELECT u.username,
         COALESCE(q.quiz_total, 0) + COALESCE(t.test_total, 0) AS total_score
  FROM users u
  LEFT JOIN (
      SELECT user_id, SUM(quiz_score) AS quiz_total
      FROM user_scores
      GROUP BY user_id
  ) q ON u.id = q.user_id
  LEFT JOIN (
      SELECT utp.user_id, SUM(utp.score) AS test_total
      FROM user_test_progress utp
      INNER JOIN (
          SELECT user_id, topic_id, MAX(date_taken) AS latest
          FROM user_test_progress
          GROUP BY user_id, topic_id
      ) latest_attempt
        ON utp.user_id = latest_attempt.user_id
       AND utp.topic_id = latest_attempt.topic_id
       AND utp.date_taken = latest_attempt.latest
      GROUP BY utp.user_id
  ) t ON u.id = t.user_id
  GROUP BY u.id
  ORDER BY total_score DESC
";


$result = $conn->query($sql);

// Topic mapping
$topicsMap = [
    'Network Security' => 1,
    'Ethical Hacking' => 2,
    'Cryptography' => 3,
    'Threat Analysis' => 4,
    'Digital Forensics' => 5
];

// ✅ Unified progress scores (quiz + test in one query)
$progressScores = [];
$progressStmt = $conn->prepare("
  SELECT t.title AS topic,
         us.quiz_score,
         us.test_score,
         (SELECT COUNT(*) FROM quiz_questions WHERE topic_id = t.id) AS quiz_total
  FROM topics t
  LEFT JOIN user_scores us 
    ON us.topic_id = t.id AND us.user_id = ?
");
$progressStmt->bind_param("i", $userId);
$progressStmt->execute();
$pResult = $progressStmt->get_result();
while ($row = $pResult->fetch_assoc()) {
    $progressScores[$row['topic']] = [
        'quiz_score' => $row['quiz_score'] ?? 0,
        'quiz_total' => $row['quiz_total'] ?? 0,
        'test_score' => $row['test_score'] ?? 0,
        'test_total' => 10 // fixed per test
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      background-color: #f9f9f9;
      color: #222;
    }
    .dashboard-container {
      max-width: 1000px;
      margin: 2rem auto;
      padding: 2rem;
      background: rgba(0, 0, 0, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
      backdrop-filter: blur(10px);
      color: black;
      text-align: center;
    }
    .dashboard-container h2 {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: #00ffcc;
    }
    .dashboard-container table {
      width: 100%;
      margin-top: 1rem;
      border-collapse: collapse;
      color: #fff;
    }
    .dashboard-container th,
    .dashboard-container td {
      padding: 12px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .dashboard-container th {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .fade-row {
      opacity: 0;
      transform: translateY(10px);
      animation: fadeInRow 0.6s ease forwards;
    }
    @keyframes fadeInRow {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .top-performer {
      display: inline-block;
      margin-left: 8px;
      padding: 2px 6px;
      background: #ff9900;
      color: white;
      font-size: 0.8rem;
      border-radius: 12px;
      font-weight: bold;
    }
    .username-display {
      font-weight: bold;
      color: #00bcd4;
      margin-left: auto;
    }

    .dashboard-container table,
    .dashboard-container th,
    .dashboard-container td {
      color: black !important;
      background-color: white !important;
    }

    .dashboard-container h1,
    .dashboard-container p {
      color: white;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="dashboard-container">
  <h1 style="color: white;">Welcome back, <?= htmlspecialchars($username) ?>!</h1>
  <p style="color: white;">Explore topics, track your progress, and see how you compare with others.</p>

  <!-- Leaderboard Preview -->
  <div class="leaderboard-preview">
    <h2>Leaderboard Preview</h2>
    <p><a href="leaderboard.php">Click here to view full leaderboard</a></p>
    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Username</th>
          <th>Total Score</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $rank = 1;
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
            $badge = $rank === 1 ? ' 🥇' : ($rank === 2 ? ' 🥈' : ($rank === 3 ? ' 🥉' : ''));
            $topTag = $row['total_score'] >= 8 ? '<span class="top-performer">🔥</span>' : '';
        ?>
          <tr class="fade-row" style="animation-delay: <?= $rank * 0.2 ?>s">
            <td><?= $rank ?></td>
            <td><?= htmlspecialchars($row['username']) . $badge ?></td>
            <td><?= $row['total_score'] ?> <?= $topTag ?></td>
          </tr>
        <?php $rank++; endwhile; else: ?>
          <tr><td colspan="3">No scores yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Progress Breakdown -->
  <div class="user-progress" style="margin-top: 3rem;">
    <h2>Your Progress</h2>
    <table>
      <thead>
        <tr>
          <th>Topic</th>
          <th>Type</th>
          <th>Your Score / Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;

        foreach ($topicsMap as $title => $id):
          $quiz = [
              'score' => $progressScores[$title]['quiz_score'] ?? 0,
              'total' => $progressScores[$title]['quiz_total'] ?? 0
          ];
          $test = [
              'score' => $progressScores[$title]['test_score'] ?? 0,
              'total' => $progressScores[$title]['test_total'] ?? 10
          ];

          $totalScore = $quiz['score'] + $test['score'];
          $totalQuestions = $quiz['total'] + $test['total'];

          $rows = [
            ['label' => 'Quiz', 'score' => $quiz['score'], 'total' => $quiz['total']],
            ['label' => 'Test', 'score' => $test['score'], 'total' => $test['total']],
            ['label' => 'Total', 'score' => $totalScore, 'total' => $totalQuestions],
          ];

          foreach ($rows as $r):
            $status = '';
            if ($r['label'] === 'Total') {
              if ($r['score'] === 0) {
                $status = '❌ Not Started';
              } elseif ($r['score'] < $r['total']) {
                $status = '🔄 In Progress';
              } else {
                $status = '✅ Completed';
              }
            }
        ?>
        <tr class="fade-row" style="animation-delay: <?= $i * 0.2 ?>s">
          <td><?= htmlspecialchars($title) ?></td>
          <td><?= $r['label'] ?></td>
          <td>
            <?= $r['score'] ?>/<?= $r['total'] ?>
            <?php if ($r['label'] === 'Total'): ?>
              <br><span style="font-size: 0.9em;"><?= $status ?></span>
            <?php endif; ?>
          </td>
        </tr>
        <?php $i++; endforeach; endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
