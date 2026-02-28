<?php require_once __DIR__ . '/session_boot.php'; ?>


<?php
require_once 'db.php';

$feedback = [];
$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    $answers = [
        'q1' => 'c',
        'q2' => 'access zone',
        'q3' => 'b',
        'q4' => 'management',
        'q5' => 'c',
        'q6' => 'b',
        'q7' => 'access',
        'q8' => 'a',
        'q9' => 'c',
        'q10' => 'security'
    ];

    foreach ($answers as $key => $correct) {
      $userAnswer = strtolower(trim($_POST[$key] ?? ''));
      $correctNorm = strtolower(trim($correct));
  
      if ($userAnswer === $correctNorm) {
          $score++;
          $feedback[$key] = true;
      } else {
          $feedback[$key] = false;
      }
  }
  

  if (isset($_SESSION['user_id'])) {
      $userId = $_SESSION['user_id'];
      $topic = 'Threat Analysis';
      $topicId = 4;
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
  <title>Threat Analysis Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="content-container">
  <h2>Threat Analysis – Test</h2>

  <?php if ($score !== null): ?>
    <div class="score-box" id="resultBox">
  <p id="resultText"><strong>You scored <?= $score; ?> out of 10.</strong></p>
  <a href="dashboard.php" class="btn">Return to Dashboard</a>
  <p style="margin-top: 10px; font-style: italic;">You will be redirected shortly...</p>
</div>

  <?php endif; ?>

  <form method="post">

    <div class="question">
      <p>1. What is the primary challenge for security in High-Performance Computing (HPC) systems?</p>
      <label><input type="radio" name="q1" value="a"> a) Limited physical space</label><br>
      <label><input type="radio" name="q1" value="b"> b) Low computing power</label><br>
      <label><input type="radio" name="q1" value="c"> c) Diverse and complex hardware and software</label><br>
      <label><input type="radio" name="q1" value="d"> d) Simple configurations</label><br>
      <?php if (isset($feedback['q1']) && !$feedback['q1']): ?>
        <p class="feedback">❌ HPC systems face challenges from complex hardware/software combinations.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>2. Which HPC zone is directly connected to external networks and most susceptible to external attacks?</p>
      <input type="text" name="q2">
      <?php if (isset($feedback['q2']) && !$feedback['q2']): ?>
        <p class="feedback">❌ This is the <strong>Access</strong> zone.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>3. Which security method helps mitigate unauthorized access in the Access Zone?</p>
      <label><input type="radio" name="q3" value="a"> a) Firewall-only protection</label><br>
      <label><input type="radio" name="q3" value="b"> b) Multi-factor authentication (MFA)</label><br>
      <label><input type="radio" name="q3" value="c"> c) Unlimited login attempts</label><br>
      <label><input type="radio" name="q3" value="d"> d) No user authentication required</label><br>
      <?php if (isset($feedback['q3']) && !$feedback['q3']): ?>
        <p class="feedback">❌ MFA adds an extra layer of security to prevent unauthorized logins.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>4. The ______ zone is responsible for managing the entire HPC system.</p>
      <input type="text" name="q4">
      <?php if (isset($feedback['q4']) && !$feedback['q4']): ?>
        <p class="feedback">❌ This refers to the <strong>Management</strong> zone.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>5. What type of threats is associated with the Management Zone due to privileged processes?</p>
      <label><input type="radio" name="q5" value="a"> a) Denial-of-service attacks</label><br>
      <label><input type="radio" name="q5" value="b"> b) Side-channel attacks</label><br>
      <label><input type="radio" name="q5" value="c"> c) Privilege escalation</label><br>
      <label><input type="radio" name="q5" value="d"> d) Authentication attacks</label><br>
      <?php if (isset($feedback['q5']) && !$feedback['q5']): ?>
        <p class="feedback">❌ Privilege escalation exploits elevated access rights in privileged processes.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>6. Exploitation of multi-tenancy environments in the HPC Zone can lead to which attack?</p>
      <label><input type="radio" name="q6" value="a"> a) Website defacement</label><br>
      <label><input type="radio" name="q6" value="b"> b) Side-channel attacks</label><br>
      <label><input type="radio" name="q6" value="c"> c) Phishing attacks</label><br>
      <label><input type="radio" name="q6" value="d"> d) Password guessing attacks</label><br>
      <?php if (isset($feedback['q6']) && !$feedback['q6']): ?>
        <p class="feedback">❌ Side-channel attacks target shared environments like those in HPC.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>7. Granular ______ control capabilities are necessary to restrict database access in HPC environments.</p>
      <input type="text" name="q7">
      <?php if (isset($feedback['q7']) && !$feedback['q7']): ?>
        <p class="feedback">❌ This refers to <strong>access</strong> control.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>8. Which technology can pose threats such as container escape in the HPC environment?</p>
      <label><input type="radio" name="q8" value="a"> a) Virtualization technologies</label><br>
      <label><input type="radio" name="q8" value="b"> b) POSIX permissions</label><br>
      <label><input type="radio" name="q8" value="c"> c) Multi-factor authentication</label><br>
      <label><input type="radio" name="q8" value="d"> d) Physical security controls</label><br>
      <?php if (isset($feedback['q8']) && !$feedback['q8']): ?>
        <p class="feedback">❌ Virtualization tools like containers can be exploited if not secured.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>9. Which scenario is considered a major threat source in the HPC Zone due to extreme resource consumption?</p>
      <label><input type="radio" name="q9" value="a"> a) Properly configured systems</label><br>
      <label><input type="radio" name="q9" value="b"> b) Authorized system use</label><br>
      <label><input type="radio" name="q9" value="c"> c) Accidental misconfiguration</label><br>
      <label><input type="radio" name="q9" value="d"> d) Strong security protocols</label><br>
      <?php if (isset($feedback['q9']) && !$feedback['q9']): ?>
        <p class="feedback">❌ Misconfiguration can lead to performance degradation or outages.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>10. HPC systems often struggle with balancing performance and ______.</p>
      <input type="text" name="q10">
      <?php if (isset($feedback['q10']) && !$feedback['q10']): ?>
        <p class="feedback">❌ HPC systems need to balance performance and <strong>security</strong>.</p>
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
