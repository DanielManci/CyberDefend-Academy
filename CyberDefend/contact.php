<?php
require_once 'db.php';
session_start();

$username = $_SESSION['username'] ?? null;
$contactMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($message)) {
        $contactMessage = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contactMessage = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $contactMessage = "✅ Your message has been sent successfully.";
        } else {
            $contactMessage = "❌ Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>


<main class="content-container">
  <section class="contact-section">
    <h2>Contact Us</h2>
    <p>If you have any questions, suggestions, or issues, please use the form below. We’ll get back to you as soon as possible.</p>

    <?php if ($contactMessage): ?>
      <p class="feedback-message"><?php echo htmlspecialchars($contactMessage); ?></p>
    <?php endif; ?>

    <form method="post" class="contact-form">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </section>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php" class="active">Contact</a> |
  <a href="about.php">About</a>
</footer>

</body>
</html>
