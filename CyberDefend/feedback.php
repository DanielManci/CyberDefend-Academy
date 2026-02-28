<?php
require_once 'db.php';
session_start();

$feedbackMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($message)) {
        $feedbackMessage = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedbackMessage = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $feedbackMessage = "✅ Thank you! Your feedback has been submitted.";
        } else {
            $feedbackMessage = "❌ Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Feedback - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>


<main class="content-container">
  <section class="feedback-section">
    <h2>We Value Your Feedback</h2>
    <p>Have thoughts about CyberDefend Academy? Let us know below!</p>

    <?php if ($feedbackMessage): ?>
      <p class="feedback-message"><?php echo htmlspecialchars($feedbackMessage); ?></p>
    <?php endif; ?>

    <form method="post" class="feedback-form">
  <label for="name">Your Name</label>
  <input type="text" id="name" name="name" required>

  <label for="email">Your Email</label>
  <input type="email" id="email" name="email" required>

  <label for="message">Your Message</label>
  <textarea id="message" name="message" rows="5" required></textarea>

  <button type="submit">Submit Feedback</button>
</form>

  </section>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
