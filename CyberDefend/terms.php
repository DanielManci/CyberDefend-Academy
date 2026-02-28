<?php
session_start();
$username = $_SESSION['username'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Terms of Use - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <div class="logo">CyberDefend Academy</div>
  <nav>
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="topics.php">Topics</a></li>
      <li><a href="forum.php">Forum</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="logout.php">Logout</a></li>
      <?php if ($username): ?>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="login.php">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>

<main class="content-container">
  <section class="terms-section">
    <h2>Terms of Use</h2>

    <p>By accessing and using the CyberDefend Academy platform, you agree to the following terms and conditions. If you do not agree with any part of these terms, you should not use this platform.</p>

    <h3>1. User Responsibilities</h3>
    <p>Users are expected to:</p>
    <ul>
      <li>Provide accurate information during registration</li>
      <li>Use the platform only for educational and non-commercial purposes</li>
      <li>Respect other users and refrain from posting inappropriate or offensive content</li>
    </ul>

    <h3>2. Content Ownership</h3>
    <p>All educational content, quizzes, and tutorials provided on CyberDefend Academy are the intellectual property of the platform and its creators. Users are not permitted to copy, redistribute, or republish any materials without permission.</p>

    <h3>3. User-Generated Content</h3>
    <p>Posts made in the forum represent the views of individual users and not of CyberDefend Academy. We reserve the right to remove any content deemed inappropriate or in violation of these terms.</p>

    <h3>4. Platform Availability</h3>
    <p>We strive to ensure the platform is always available but cannot guarantee uninterrupted access. Maintenance or technical issues may cause temporary downtime.</p>

    <h3>5. Limitation of Liability</h3>
    <p>CyberDefend Academy is provided “as is” without warranties of any kind. We are not liable for any losses or damages resulting from the use of the platform.</p>

    <h3>6. Updates to Terms</h3>
    <p>We may update these terms from time to time. Users are encouraged to review this page periodically for changes.</p>

    <p><strong>Last updated:</strong> April 2, 2025</p>
  </section>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php" class="active">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
