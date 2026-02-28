<?php
session_start();
require_once 'db.php';

$sql = "
  SELECT u.username, 
         COALESCE(SUM(us.quiz_score + us.test_score), 0) AS total_score
  FROM users u
  LEFT JOIN user_scores us ON u.id = us.user_id
  GROUP BY u.id
  ORDER BY total_score DESC
";

$result = $conn->query($sql);
$leaderboard = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      background-color: #f9f9f9;
      color: #222;
    }
    .leaderboard-container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      text-align: center;
      background: rgba(0, 0, 0, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
      backdrop-filter: blur(10px);
      color: white;
    }
    .leaderboard-container table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      color: black;
    }
    .leaderboard-container th, .leaderboard-container td {
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 12px;
    }
    .leaderboard-container th {
      background-color: rgba(255, 255, 255, 0.1);
      font-weight: bold;
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
  </style>
</head>
<body>

<?php include 'header.php'; ?>


<main class="leaderboard-container">
  <h1>Leaderboard</h1>
  <p>Compare your scores with others</p>

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
      foreach ($leaderboard as $row):
        $badge = '';
        if ($rank === 1) $badge = ' 🥇';
        elseif ($rank === 2) $badge = ' 🥈';
        elseif ($rank === 3) $badge = ' 🥉';

        $topPerformer = $row['total_score'] >= 8 ? '<span class="top-performer">🔥 Top Performer</span>' : '';
      ?>
        <tr class="fade-row" style="animation-delay: <?= $rank * 0.2 ?>s">
          <td><?= $rank ?></td>
          <td><?= htmlspecialchars($row['username']) . $badge ?></td>
          <td><?= $row['total_score'] ?> <?= $topPerformer ?></td>
        </tr>
      <?php
      $rank++;
      endforeach;
      ?>
    </tbody>
  </table>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
