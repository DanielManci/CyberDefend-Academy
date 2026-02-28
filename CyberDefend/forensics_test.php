<?php
session_start();
require_once 'db.php';

$feedback = [];
$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    $answers = [
        'q1' => 'b',
        'q2' => 'memory',
        'q3' => 'b',
        'q4' => 'c',
        'q5' => 'memory',
        'q6' => 'c',
        'q7' => 'b',
        'q8' => 'c',
        'q9' => 'chain of custody',
        'q10' => 'date and time'
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
      $topic = 'Digital Forensics';
      $topicId = 5;
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
  <title>Digital Forensics Test</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="content-container">
  <h2>Digital Forensics – Test</h2>

  <?php if ($score !== null): ?>
    <div class="score-box" id="resultBox">
  <p id="resultText"><strong>You scored <?= $score; ?> out of 10.</strong></p>
  <a href="dashboard.php" class="btn">Return to Dashboard</a>
  <p style="margin-top: 10px; font-style: italic;">You will be redirected shortly...</p>
</div>

  <?php endif; ?>

  <form method="post">

    <div class="question">
      <p>1. Which digital forensic phase involves filtering and extracting relevant data from collected evidence?</p>
      <label><input type="radio" name="q1" value="a"> a) Collection</label><br>
      <label><input type="radio" name="q1" value="b"> b) Examination</label><br>
      <label><input type="radio" name="q1" value="c"> c) Analysis</label><br>
      <label><input type="radio" name="q1" value="d"> d) Reporting</label><br>
      <?php if (isset($feedback['q1']) && !$feedback['q1']): ?>
        <p class="feedback">❌ This step involves narrowing down large volumes of data to what's useful.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>2. The two main types of forensic images taken from Windows systems are disk images and ______ images.</p>
      <input type="text" name="q2">
      <?php if (isset($feedback['q2']) && !$feedback['q2']): ?>
        <p class="feedback">❌ Think about volatile data that disappears after shutdown. The answer is <strong>memory</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>3. DFIR combines digital forensics with:</p>
      <label><input type="radio" name="q3" value="a"> a) Cloud storage management</label><br>
      <label><input type="radio" name="q3" value="b"> b) Incident response</label><br>
      <label><input type="radio" name="q3" value="c"> c) Database analysis</label><br>
      <label><input type="radio" name="q3" value="d"> d) Network administration</label><br>
      <?php if (isset($feedback['q3']) && !$feedback['q3']): ?>
        <p class="feedback">❌ DFIR is about responding to incidents while preserving evidence.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>4. The primary difference between digital forensics and computer forensics is that computer forensics specifically focuses on:</p>
      <label><input type="radio" name="q4" value="a"> a) Mobile phones only</label><br>
      <label><input type="radio" name="q4" value="b"> b) Any digital device</label><br>
      <label><input type="radio" name="q4" value="c"> c) Computing devices</label><br>
      <label><input type="radio" name="q4" value="d"> d) Network devices</label><br>
      <?php if (isset($feedback['q4']) && !$feedback['q4']): ?>
        <p class="feedback">❌ Computer forensics focuses specifically on devices like laptops and desktops.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>5. The forensic image type that captures volatile data from running processes is called a ______ image.</p>
      <input type="text" name="q5">
      <?php if (isset($feedback['q5']) && !$feedback['q5']): ?>
        <p class="feedback">❌ It's a <strong>volatility</strong> or <strong>memory</strong> image.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>6. Which type of digital forensic investigation involves analyzing network traffic logs?</p>
      <label><input type="radio" name="q6" value="a"> a) Email forensics</label><br>
      <label><input type="radio" name="q6" value="b"> b) Cloud forensics</label><br>
      <label><input type="radio" name="q6" value="c"> c) Network forensics</label><br>
      <label><input type="radio" name="q6" value="d"> d) Database forensics</label><br>
      <?php if (isset($feedback['q6']) && !$feedback['q6']): ?>
        <p class="feedback">❌ Network forensics involves reviewing traffic data like logs and packets.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>7. What ensures that collected digital evidence has not been altered?</p>
      <label><input type="radio" name="q7" value="a"> a) Authorization forms</label><br>
      <label><input type="radio" name="q7" value="b"> b) Write blockers</label><br>
      <label><input type="radio" name="q7" value="c"> c) GPS logs</label><br>
      <label><input type="radio" name="q7" value="d"> d) Keyword searches</label><br>
      <?php if (isset($feedback['q7']) && !$feedback['q7']): ?>
        <p class="feedback">❌ Write blockers are used to prevent accidental modification of the data.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>8. Digital forensics primarily ensures that digital evidence is:</p>
      <label><input type="radio" name="q8" value="a"> a) Easily deleted</label><br>
      <label><input type="radio" name="q8" value="b"> b) Quickly transmitted</label><br>
      <label><input type="radio" name="q8" value="c"> c) Admissible in court</label><br>
      <label><input type="radio" name="q8" value="d"> d) Regularly updated</label><br>
      <?php if (isset($feedback['q8']) && !$feedback['q8']): ?>
        <p class="feedback">❌ The main goal is legal admissibility of digital evidence.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>9. A ________ is a formal document detailing evidence description, collection details, storage, and access records.</p>
      <input type="text" name="q9">
      <?php if (isset($feedback['q9']) && !$feedback['q9']): ?>
        <p class="feedback">❌ The correct answer is <strong>chain of custody</strong>.</p>
      <?php endif; ?>
    </div>

    <div class="question">
      <p>10. EXIF data provides metadata about digital images, including camera model, GPS location, and __________.</p>
      <input type="text" name="q10">
      <?php if (isset($feedback['q10']) && !$feedback['q10']): ?>
        <p class="feedback">❌ EXIF data includes the <strong>capture date/time</strong>.</p>
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
