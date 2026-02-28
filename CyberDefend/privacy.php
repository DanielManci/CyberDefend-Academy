<?php
session_start();
$username = $_SESSION['username'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Privacy Policy - CyberDefend Academy</title>
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
  <section class="privacy-policy">
    <h2>Privacy Policy</h2>

    <p>At CyberDefend Academy, we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy outlines how we collect, use, store, and protect your data.</p>

    <h3>1. Data Collection</h3>
    <p>We may collect the following types of personal information:</p>
    <ul>
      <li>Username and email address during registration</li>
      <li>Messages submitted through the feedback form</li>
      <li>Forum posts created by logged-in users</li>
      <li>Basic usage statistics (e.g., quiz performance, page visits)</li>
    </ul>

    <h3>2. How We Use Your Data</h3>
    <p>Your information is used to:</p>
    <ul>
      <li>Create and manage your account</li>
      <li>Display your username in forums or dashboards</li>
      <li>Improve the functionality and user experience of the platform</li>
      <li>Respond to feedback and support requests</li>
    </ul>

    <h3>3. Data Storage and Security</h3>
    <p>Your personal data is stored securely on our university-hosted servers. We take appropriate technical and organizational measures to protect your data against unauthorized access, alteration, or loss.</p>

    <h3>4. Sharing of Information</h3>
    <p>We do not sell, rent, or share your personal information with third parties. Data may only be accessed by system administrators or teaching staff for grading and support purposes.</p>

    <h3>5. Your Rights Under GDPR</h3>
    <p>As a UK-based user, you have the right to:</p>
    <ul>
      <li>Access the data we hold about you</li>
      <li>Request corrections or updates</li>
      <li>Request deletion of your data</li>
      <li>Withdraw your consent at any time</li>
    </ul>
    <p>To exercise your rights, please contact us using the form on the <a href="contact.php">Contact page</a>.</p>

    <h3>6. Changes to This Policy</h3>
    <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with the date of the last update.</p>

    <p><strong>Last updated:</strong> April 2, 2025</p>
  </section>
</main>

<footer>
  <a href="privacy.php" class="active">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
