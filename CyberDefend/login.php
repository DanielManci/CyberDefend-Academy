<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php'; // [ADD] CSRF helpers

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); // [ADD] Validate token BEFORE reading $_POST

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Step 3: optional rehash if algorithm/cost policy changed
                $options = ['cost' => 12];
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT, $options)) {
                    $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
                    $u = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $u->bind_param("si", $newHash, $user['id']);
                    $u->execute();
                    $u->close();
                }

                // Step 3: regenerate session ID on successful login
                session_regenerate_id(true);

                $_SESSION['username'] = $username;
                $_SESSION['user_id']  = (int)$user['id'];

                // Close the original statement before redirecting
                $stmt->close();

                header("Location: home.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Username not found.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CyberDefend Academy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="auth-page">
  <div class="auth-box">
    <h2>Login to Your Account</h2>

    <?php if (!empty($message)): ?>
      <p class="error"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
      <?= csrf_field() ?> <!-- [ADD] Hidden CSRF token field -->
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Login</button>
    </form>

    <p class="auth-note">No account yet? <a href="register.php">Register here</a>.</p>
  </div>
</main>
</body>
</html>
