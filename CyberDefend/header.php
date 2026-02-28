<?php
// --- Security and session policy (must run before any output) ---

// Start or configure the session
if (session_status() === PHP_SESSION_NONE) {
  // Only set cookie flags BEFORE the session starts
  ini_set('session.cookie_httponly', '1');
  ini_set(
    'session.cookie_secure',
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? '1' : '0'
  );
  ini_set('session.cookie_samesite', 'Lax');

  session_start();
} else {
  // Session already active (e.g., page called session_start() earlier).
  // Do NOT call ini_set for session settings here.
}

// Baseline security headers (only if headers not sent yet)
if (!headers_sent()) {
header(
  "Content-Security-Policy: "
  . "default-src 'self'; "
  . "style-src 'self' 'unsafe-inline'; "
  . "img-src 'self' data:; "
  . "script-src 'self'; "
  . "frame-src https://drive.google.com https://docs.google.com; "
  . "media-src 'self' https://drive.google.com https://docs.google.com https://*.googleusercontent.com; "
  . "object-src 'none'; "
  . "frame-ancestors 'none'; "
  . "form-action 'self'"
);
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

  header("Referrer-Policy: strict-origin-when-cross-origin");
  header("X-Content-Type-Options: nosniff");
  header("X-Frame-Options: DENY");
}

// DB as you had it
require_once 'db.php';

$username = $_SESSION['username'] ?? null;
?>

<header>
  <div class="logo">CyberDefend Academy</div>
  <nav>
    <ul>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="topics.php">Topics</a></li>
      <li><a href="leaderboard.php">Leaderboard</a></li>
      <li><a href="forum.php">Forum</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="logout.php">Logout</a></li>
      <?php if ($username): ?>
        <li class="username-display">👤 <?= htmlspecialchars($username) ?></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
