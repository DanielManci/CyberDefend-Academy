<?php
session_start();
require_once 'db.php';

$feedback = [];
$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    $answers = [
        'q1' => 'b', 
        'q2' => 'non-repudiation', 
        'q3' => 'c', 
        'q4' => 'b', 
        'q5' => 'blocks', 
        'q6' => 'd', 
        'q7' => 'b', 
        'q8' => 'man-in-the-middle', 
        'q9' => 'b', 
        'q10' => 'c'
    ];

    foreach ($answers as $key => $correct) {
      $userAnswer = strtolower(trim($_POST[$key] ?? ''));
      if ($userAnswer === strtolower($correct)) {
          $score++;
          $feedback[$key] = true;
      } else {
          $feedback[$key] = false;
      }
  }

  if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $topic = 'Cryptography';
      $topicId = 3;
      $totalQuestions = count($answers);

      $stmt = $conn->prepare("
          INSERT INTO user_test_progress (user_id, topic_id, topic, score, total_questions, date_taken) 
          VALUES (?, ?, ?, ?, ?, NOW())
      ");
      $stmt->bind_param("iisii", $userId, $topicId, $topic, $score, $totalQuestions);
      $stmt->execute();
      $stmt->close();

      $stmt2 = $conn->prepare("
          INSERT INTO user_scores (user_id, topic_id, quiz_score, test_score) 
          VALUES (?, ?, 0, ?)
          ON DUPLICATE KEY UPDATE test_score = VALUES(test_score)
      ");
      $stmt2->bind_param("iii", $userId, $topicId, $score);
      $stmt2->execute();
      $stmt2->close();
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cryptography Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="content-container">
  <h2>Cryptography – Test</h2>

  <?php if ($score !== null): ?>
    <div class="score-box" id="resultBox">
  <p id="resultText"><strong>You scored <?= $score; ?> out of 10.</strong></p>
  <a href="dashboard.php" class="btn">Return to Dashboard</a>
  <p style="margin-top: 10px; font-style: italic;">You will be redirected shortly...</p>
</div>

  <?php endif; ?>

  <form method="post">

    <div class="question">
      <p>1. What is the primary purpose of cryptography?</p>
      <label><input type="radio" name="q1" value="a"> a) Speeding up internet connections</label><br>
      <label><input type="radio" name="q1" value="b"> b) Protecting data from unauthorized access</label><br>
      <label><input type="radio" name="q1" value="c"> c) Compressing large files</label><br>
      <label><input type="radio" name="q1" value="d"> d) Improving network performance</label><br>
      <?php if (isset($feedback['q1']) && !$feedback['q1']): ?>
        <p class="feedback">❌ Cryptography protects data from unauthorized access.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>2. "______ ensures that a sender cannot deny sending a particular message."</p>
      <input type="text" name="q2">
      <?php if (isset($feedback['q2']) && !$feedback['q2']): ?>
        <p class="feedback">❌ This principle is called <strong>non-repudiation</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>3. What type of cryptography uses the same key for both encryption and decryption?</p>
      <label><input type="radio" name="q3" value="a"> a) Asymmetric cryptography</label><br>
      <label><input type="radio" name="q3" value="b"> b) Quantum cryptography</label><br>
      <label><input type="radio" name="q3" value="c"> c) Symmetric cryptography</label><br>
      <label><input type="radio" name="q3" value="d"> d) Elliptic Curve Cryptography (ECC)</label><br>
      <?php if (isset($feedback['q3']) && !$feedback['q3']): ?>
        <p class="feedback">❌ Symmetric cryptography uses a single shared key for encryption and decryption.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>4. Quantum cryptography primarily uses what to encrypt data?</p>
      <label><input type="radio" name="q4" value="a"> a) Public-private key pairs</label><br>
      <label><input type="radio" name="q4" value="b"> b) Photon-based encryption</label><br>
      <label><input type="radio" name="q4" value="c"> c) Hashing algorithms</label><br>
      <label><input type="radio" name="q4" value="d"> d) Caesar Cipher method</label><br>
      <?php if (isset($feedback['q4']) && !$feedback['q4']): ?>
        <p class="feedback">❌ Quantum cryptography uses <strong>photon-based encryption</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>5. "AES encrypts data in fixed size ______."</p>
      <input type="text" name="q5">
      <?php if (isset($feedback['q5']) && !$feedback['q5']): ?>
        <p class="feedback">❌ AES encrypts data in fixed-size <strong>blocks</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>6. Which cryptographic method is used by blockchain-based cryptocurrencies like Bitcoin?</p>
      <label><input type="radio" name="q6" value="a"> a) Quantum Cryptography</label><br>
      <label><input type="radio" name="q6" value="b"> b) Caesar Cipher</label><br>
      <label><input type="radio" name="q6" value="c"> c) RSA encryption</label><br>
      <label><input type="radio" name="q6" value="d"> d) Elliptic Curve Cryptography (ECC)</label><br>
      <?php if (isset($feedback['q6']) && !$feedback['q6']): ?>
        <p class="feedback">❌ Bitcoin commonly uses <strong>ECC</strong> for transactions.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>7. Which principle of cryptography ensures data is not altered without authorization?</p>
      <label><input type="radio" name="q7" value="a"> a) Confidentiality</label><br>
      <label><input type="radio" name="q7" value="b"> b) Integrity</label><br>
      <label><input type="radio" name="q7" value="c"> c) Non-repudiation</label><br>
      <label><input type="radio" name="q7" value="d"> d) Authentication</label><br>
      <?php if (isset($feedback['q7']) && !$feedback['q7']): ?>
        <p class="feedback">❌ <strong>Integrity</strong> ensures the data hasn't been tampered with.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>8. "TLS encryption helps prevent ______ attacks during web browsing."</p>
      <input type="text" name="q8">
      <?php if (isset($feedback['q8']) && !$feedback['q8']): ?>
        <p class="feedback">❌ TLS helps prevent <strong>Man-in-the-Middle</strong> attacks.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>9. Which of the following is an example of a hash function?</p>
      <label><input type="radio" name="q9" value="a"> a) RSA</label><br>
      <label><input type="radio" name="q9" value="b"> b) SHA-256</label><br>
      <label><input type="radio" name="q9" value="c"> c) ECC</label><br>
      <label><input type="radio" name="q9" value="d"> d) AES-256</label><br>
      <?php if (isset($feedback['q9']) && !$feedback['q9']): ?>
        <p class="feedback">❌ <strong>SHA-256</strong> is a secure hash algorithm used for data integrity.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>10. In asymmetric cryptography, what key is used to decrypt messages?</p>
      <label><input type="radio" name="q10" value="a"> a) Public key</label><br>
      <label><input type="radio" name="q10" value="b"> b) Shared key</label><br>
      <label><input type="radio" name="q10" value="c"> c) Private key</label><br>
      <label><input type="radio" name="q10" value="d"> d) Quantum key</label><br>
      <?php if (isset($feedback['q10']) && !$feedback['q10']): ?>
        <p class="feedback">❌ The <strong>private key</strong> is used for decryption in asymmetric encryption.</p>
      <?php endif; ?>
    </div>

    <button type="submit" class="btn">Submit Test</button>
  </form>
</main>
<?php if ($score !== null): ?>
<script>
  // Redirect after 5 seconds
  setTimeout(() => {
    window.location.href = "dashboard.php";
  }, 5000);
</script>
<?php endif; ?>

</body>
</html>
