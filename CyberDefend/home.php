<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CyberDefend Academy - Home</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include 'header.php'; ?>


  <main class="home-hero-bg">

    <!-- Auth Buttons (only show when NOT logged in) -->
    <?php if (!$isLoggedIn): ?>
      <div class="auth-buttons">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
      </div>
    <?php endif; ?>

    <div class="hero">
      <h1>Welcome to CyberDefend Academy<?= $isLoggedIn ? ', ' . htmlspecialchars($username) : '' ?></h1>
      <p>Learn, Practice, and Master Cybersecurity</p>
      <a href="topics.php" class="btn">Start Learning</a>
    </div>
  </main>

  <footer class="footer">
    <a href="#">Privacy</a>
    <a href="#">Terms</a>
    <a href="#">Contact</a>
    <a href="#">About</a>
  </footer>
</body>
</html>
