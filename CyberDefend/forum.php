<?php
session_start();
require_once 'db.php';

$username = $_SESSION['username'] ?? null;
$submissionMessage = "";

// Handle new post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['message'], $username)) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (empty($title) || empty($message)) {
        $submissionMessage = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO forum_posts (username, title, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $title, $message);

        if ($stmt->execute()) {
            header("Location: forum.php?success=1");
            exit();
        } else {
            $submissionMessage = "❌ Failed to submit post. Please try again.";
        }

        $stmt->close();
    }
}

// Fetch all posts
$posts = $conn->query("SELECT * FROM forum_posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CyberDefend Forum</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .reply {
      margin: 10px 0 20px 20px;
      background: #f2f2f2;
      padding: 10px;
      border-left: 3px solid #00bcd4;
      border-radius: 6px;
    }
    .forum-post form textarea {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-top: 10px;
    }
    .forum-post form button {
      margin-top: 8px;
      background-color: #00bcd4;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="content-container">
  <section class="forum-section">
    <h2>CyberDefend Forum</h2>

    <?php if ($username): ?>
      <div class="forum-form">
        <h3>Start a New Discussion</h3>
        <?php if (isset($_GET['success'])): ?>
          <p class="feedback-message">✅ Post submitted successfully!</p>
        <?php elseif (!empty($submissionMessage)): ?>
          <p class="feedback-message"><?php echo htmlspecialchars($submissionMessage); ?></p>
        <?php endif; ?>

        <form method="post">
          <input type="text" name="title" placeholder="Post Title" required>
          <textarea name="message" rows="5" placeholder="Your message..." required></textarea>
          <button type="submit">Post</button>
        </form>
      </div>
    <?php else: ?>
      <p>Please <a href="login.php">login</a> to create a new post.</p>
    <?php endif; ?>

    <hr>

    <div class="forum-posts">
      <h3>Recent Discussions</h3>
      <?php if ($posts && $posts->num_rows > 0): ?>
        <?php while ($post = $posts->fetch_assoc()): ?>
          <div class="forum-post">
            <h4><?php echo htmlspecialchars($post['title']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($post['message'])); ?></p>
            <small>Posted by <strong><?php echo htmlspecialchars($post['username']); ?></strong> on <?php echo $post['created_at']; ?></small>

            <!-- Replies -->
            <?php
              $postId = $post['id'];
              $replyStmt = $conn->prepare("SELECT * FROM forum_replies WHERE post_id = ? ORDER BY created_at ASC");
              $replyStmt->bind_param("i", $postId);
              $replyStmt->execute();
              $replies = $replyStmt->get_result();
              while ($reply = $replies->fetch_assoc()):
            ?>
              <div class="reply">
                <strong><?php echo htmlspecialchars($reply['username']); ?>:</strong>
                <p><?php echo nl2br(htmlspecialchars($reply['reply'])); ?></p>
                <small><?php echo $reply['created_at']; ?></small>
              </div>
            <?php endwhile; ?>

            <!-- Reply form -->
            <?php if ($username): ?>
              <form method="POST" action="post_reply.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <textarea name="reply" rows="2" placeholder="Write a reply..." required></textarea>
                <button type="submit">Reply</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No posts yet. Be the first to start a discussion!</p>
      <?php endif; ?>
    </div>
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
