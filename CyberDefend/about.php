<?php
session_start();
$username = $_SESSION['username'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>


<main class="content-container">
  <section class="about-section">
    <h2>About CyberDefend Academy</h2>

    <p><strong>CyberDefend Academy</strong> is a cybersecurity learning platform designed to help students and beginners build a solid foundation in core security concepts.</p>

    <p>Our goal is to create an interactive environment where users can:</p>
    <ul>
      <li>Learn about cybersecurity through accessible and practical content</li>
      <li>Engage with quizzes and interactive activities</li>
      <li>Join discussions and collaborate with others</li>
      <li>Track progress and continuously improve their skills</li>
    </ul>

    <h3>Technologies Used</h3>
    <p>This platform is built using the following technologies:</p>
    <ul>
      <li>HTML & CSS for structure and styling</li>
      <li>PHP for server-side scripting and functionality</li>
      <li>MySQL for database management</li>
      <li>JavaScript for form validation and interactivity (optional)</li>
    </ul>

    <h3>Project Purpose</h3>
    <p>This platform was developed as part of a final-year project for the BSc Computing degree at Arden University, aiming to demonstrate full-stack web development, database integration, and cybersecurity-focused learning design.</p>

    <p><em>Thank you for visiting CyberDefend Academy — stay safe, stay secure!</em></p>
  </section>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php" class="active">About</a>
</footer>

</body>
</html>
