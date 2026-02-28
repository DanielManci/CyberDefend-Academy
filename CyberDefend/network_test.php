<?php require_once __DIR__ . '/session_boot.php'; ?>


<?php
require_once 'db.php';

$feedback = [];
$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $score = 0;

    $answers = [
        'q1' => 'b',
        'q2' => 'command',
        'q3' => 'c',
        'q4' => 'c',
        'q5' => 'lan',
        'q6' => 'b',
        'q7' => 'b',
        'q8' => 'open-source',
        'q9' => 'c',
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

    // Normalised, typed values
    $userId         = (int)$_SESSION['user_id'];
    $topic          = 'Network Security';
    $topicId        = 1;
    $totalQuestions = (int)count($answers);

    // ✅ Save to user_test_progress (prepared statement with typed binds)
    $stmt = $conn->prepare("
        INSERT INTO user_test_progress (user_id, topic_id, topic, score, total_questions, date_taken) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("iisii", $userId, $topicId, $topic, $score, $totalQuestions);
    $stmt->execute();
    $stmt->close();

    // ✅ Step 4 proof: maintain quiz_score when updating test_score in user_scores
    //    1) Read current quiz_score if present
    $currentQuiz = 0;
    $stmtCheck = $conn->prepare("SELECT quiz_score FROM user_scores WHERE user_id = ? AND topic_id = ?");
    $stmtCheck->bind_param("ii", $userId, $topicId);
    $stmtCheck->execute();
    $stmtCheck->bind_result($currentQuiz);
    $stmtCheck->fetch();
    $stmtCheck->close();

    //    2) Upsert test_score while keeping quiz_score
    //       This ON DUPLICATE KEY path assumes a UNIQUE key on (user_id, topic_id).
    //       If you don’t have it, add:
    //       ALTER TABLE user_scores ADD UNIQUE KEY ux_user_topic (user_id, topic_id);
    $stmt2 = $conn->prepare("
        INSERT INTO user_scores (user_id, topic_id, quiz_score, test_score)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            test_score = VALUES(test_score),
            quiz_score = VALUES(quiz_score)
    ");
    $stmt2->bind_param("iiii", $userId, $topicId, $currentQuiz, $score);
    $stmt2->execute();
    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Network Security Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="content-container">
  <h2>Network Security – Test</h2>

  <?php if ($score !== null): ?>
    <div class="score-box" id="resultBox">
      <p id="resultText"><strong>You scored <?= $score; ?> out of 10.</strong></p>
      <a href="dashboard.php" class="btn">Return to Dashboard</a>
      <p style="margin-top: 10px; font-style: italic;">You will be redirected shortly...</p>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="question">
      <p>1. What does HTTPS use to encrypt data transmitted between a web server and a web browser?</p>
      <label><input type="radio" name="q1" value="a"> A. AES</label><br>
      <label><input type="radio" name="q1" value="b"> B. SSL/TLS</label><br>
      <label><input type="radio" name="q1" value="c"> C. DES</label><br>
      <label><input type="radio" name="q1" value="d"> D. RSA</label><br>
      <?php if (isset($feedback['q1']) && !$feedback['q1']): ?>
        <p class="feedback">❌ HTTPS uses <strong>SSL/TLS</strong> to encrypt communication between client and server.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>2. FTPS encrypts both ______ and data channels to protect against sniffing attacks.</p>
      <input type="text" name="q2">
      <?php if (isset($feedback['q2']) && !$feedback['q2']): ?>
        <p class="feedback">❌ FTPS encrypts both <strong>command</strong> and data channels to secure communication.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>3. Which of the following ports does SMTPS commonly use?</p>
      <label><input type="radio" name="q3" value="a"> A. 21 and 22</label><br>
      <label><input type="radio" name="q3" value="b"> B. 443 and 80</label><br>
      <label><input type="radio" name="q3" value="c"> C. 465 and 587</label><br>
      <label><input type="radio" name="q3" value="d"> D. 53 and 110</label><br>
      <?php if (isset($feedback['q3']) && !$feedback['q3']): ?>
        <p class="feedback">❌ SMTPS commonly uses ports <strong>465</strong> and <strong>587</strong> for secure email.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>4. DNSSEC protects against which of the following attacks?</p>
      <label><input type="radio" name="q4" value="a"> A. DDoS attacks</label><br>
      <label><input type="radio" name="q4" value="b"> B. MITM attacks</label><br>
      <label><input type="radio" name="q4" value="c"> C. DNS spoofing and cache poisoning</label><br>
      <label><input type="radio" name="q4" value="d"> D. Phishing attacks</label><br>
      <?php if (isset($feedback['q4']) && !$feedback['q4']): ?>
        <p class="feedback">❌ DNSSEC defends against <strong>DNS spoofing and cache poisoning</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>5. Typical network traffic consists of millions of packets per second exchanged among hosts on a ______ and between these hosts and the Internet.</p>
      <input type="text" name="q5">
      <?php if (isset($feedback['q5']) && !$feedback['q5']): ?>
        <p class="feedback">❌ The correct term is <strong>LAN</strong> (Local Area Network).</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>6. Which type of IDS monitors activity on individual devices?</p>
      <label><input type="radio" name="q6" value="a"> A. NIDS</label><br>
      <label><input type="radio" name="q6" value="b"> B. HIDS</</label><br>
      <label><input type="radio" name="q6" value="c"> C. Signature-based IDS</label><br>
      <label><input type="radio" name="q6" value="d"> D. Anomaly-based IDS</label><br>
      <?php if (isset($feedback['q6']) && !$feedback['q6']): ?>
        <p class="feedback">❌ <strong>HIDS</strong> (Host-based IDS) monitors activity on individual devices.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>7. Which attack involves sending malicious packets disguised as a trusted source?</p>
      <label><input type="radio" name="q7" value="a"> A. DDoS Attacks</label><br>
      <label><input type="radio" name="q7" value="b"> B. IP Spoofing</label><br>
      <label><input type="radio" name="q7" value="c"> C. Packet Sniffing</label><br>
      <label><input type="radio" name="q7" value="d"> D. MITM Attacks</label><br>
      <?php if (isset($feedback['q7']) && !$feedback['q7']): ?>
        <p class="feedback">❌ <strong>IP Spoofing</strong> disguises malicious packets as if they come from a trusted source.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>8. OSINT stands for _____ Intelligence.</p>
      <input type="text" name="q8">
      <?php if (isset($feedback['q8']) && !$feedback['q8']): ?>
        <p class="feedback">❌ OSINT stands for <strong>Open-Source</strong> Intelligence.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>9. Which statement is true about network intrusions?</p>
      <label><input type="radio" name="q9" value="a"> A. They only cause a denial of service</label><br>
      <label><input type="radio" name="q9" value="b"> B. They aim solely to consume bandwidth</label><br>
      <label><input type="radio" name="q9" value="c"> C. They attempt to consume resources, interfere with systems, or gather exploitable knowledge</label><br>
      <label><input type="radio" name="q9" value="d"> D. They always originate externally</label><br>
      <?php if (isset($feedback['q9']) && !$feedback['q9']): ?>
        <p class="feedback">❌ Network intrusions <strong>consume resources or gather knowledge</strong> for future attacks.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>10. Early Intrusion Detection Systems primarily relied on what detection method?</p>
      <label><input type="radio" name="q10" value="a"> A. Anomaly detection</label><br>
      <label><input type="radio" name="q10" value="b"> B. Heuristic analysis</label><br>
      <label><input type="radio" name="q10" value="c"> C. Signature detection</label><br>
      <label><input type="radio" name="q10" value="d"> D. Behavioural analysis</label><br>
      <?php if (isset($feedback['q10']) && !$feedback['q10']): ?>
        <p class="feedback">❌ Early IDSs used <strong>signature detection</strong> to identify known attack patterns.</p>
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
  }, 10000);
</script>
<?php endif; ?>

</body>
</html>
