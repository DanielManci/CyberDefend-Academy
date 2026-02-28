<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Step 3: set a password hashing cost policy
        $options = ['cost' => 12];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT, $options);

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $message = "An unexpected error occurred. Please try again.";
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            // Check if the error is due to duplicate entry
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = "Username or Email already exists.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - CyberDefend Academy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-page">
  <div class="auth-box">
    <h2>Create Your Account</h2>

    <?php if (!empty($message)): ?>
      <p class="error"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
        <?= csrf_field() ?>

      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Register</button>
    </form>

    <p class="auth-note">Already have an account? <a href="login.php">Login here</a>.</p>
  </div>
</main>
</body>
</html>
