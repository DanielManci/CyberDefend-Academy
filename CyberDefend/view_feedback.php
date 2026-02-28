<?php
require_once 'db.php';
session_start();

// OPTIONAL: Restrict access
// if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

$result = $conn->query("SELECT * FROM feedback ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback Submissions</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo">CyberDefend Academy</div>
  <nav>
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="view_feedback.php" class="active">View Feedback</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<main class="content-container">
  <h2>Feedback Submissions</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="feedback-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
            <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No feedback submitted yet.</p>
  <?php endif; ?>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
