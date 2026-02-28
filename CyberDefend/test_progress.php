<?php
session_start();
require_once 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$selectedTopic = $_GET['topic'] ?? '';

// Fetch all available topics
$topicResult = $conn->query("SELECT DISTINCT topic FROM user_test_progress ORDER BY topic ASC");

// Prepare leaderboard query
$sql = "
SELECT u.username, p.topic, p.score, p.total_questions, p.date_taken
FROM user_test_progress p
JOIN users u ON p.user_id = u.id
INNER JOIN (
    SELECT user_id, topic, MAX(date_taken) AS latest_date
    FROM user_test_progress
    GROUP BY user_id, topic
) latest ON latest.user_id = p.user_id AND latest.topic = p.topic AND latest.latest_date = p.date_taken
";

if (!empty($selectedTopic)) {
    $sql .= " WHERE p.topic = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedTopic);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql .= " ORDER BY p.topic, p.score DESC, p.date_taken DESC";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="content-container">
  <h2 style="text-align:center;">🏆 CyberDefend Academy – Leaderboard</h2>

  <form class="filter-form" method="get">
    <label for="topic">Filter by Topic:</label>
    <select name="topic" id="topic">
      <option value="">All Topics</option>
      <?php while ($row = $topicResult->fetch_assoc()): ?>
        <option value="<?php echo htmlspecialchars($row['topic']); ?>" <?php if ($row['topic'] === $selectedTopic) echo 'selected'; ?>>
          <?php echo htmlspecialchars($row['topic']); ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Apply</button>
  </form>

  <table class="progress-table">
    <tr>
      <th>User</th>
      <th>Topic</th>
      <th>Score</th>
      <th>Status</th>
      <th>Date Taken</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): 
      $score = $row['score'];
      $total = $row['total_questions'];
      $percentage = ($score / $total) * 100;

      // Badge color logic
      if ($percentage >= 80) {
        $badgeClass = 'green';
      } elseif ($percentage >= 50) {
        $badgeClass = 'yellow';
      } else {
        $badgeClass = 'red';
      }
    ?>
    <tr>
      <td><?php echo htmlspecialchars($row['username']); ?></td>
      <td><?php echo htmlspecialchars($row['topic']); ?></td>
      <td><?php echo $score . '/' . $total; ?></td>
      <td><span class="badge <?php echo $badgeClass; ?>"><?php echo round($percentage) . '%'; ?></span></td>
      <td><?php echo date("d M Y, H:i", strtotime($row['date_taken'])); ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</main>

</body>
</html>
