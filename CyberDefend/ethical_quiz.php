<?php
require_once 'db.php';
$topic_id = 2; // Ethical Hacking
$questions = [];

$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE topic_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ethical Hacking Quiz</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .feedback { margin-top: 10px; font-weight: bold; }
    .correct { color: green; }
    .incorrect { color: red; }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <div class="quiz-container">
    <h2>Ethical Hacking – Quick Quiz</h2>

    <form id="quizForm">
      <?php
      $qNum = 1;
      foreach ($questions as $q):
        $qid = "q$qNum";
        $type = $q['question_type'];
        $correct = htmlspecialchars(strtolower($q['correct_answer']));
        $explanation = htmlspecialchars($q['explanation']);
      ?>
      <div class="question" data-correct-answer="<?= $correct ?>" data-explanation="<?= $explanation ?>">
        <p><?= $qNum ?>. <?= htmlspecialchars($q['question']) ?></p>

        <?php if ($type === 'mcq'): ?>
          <label><input type="radio" name="<?= $qid ?>" value="<?= htmlspecialchars(strtolower($q['option_a'])) ?>"> a) <?= htmlspecialchars($q['option_a']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="<?= htmlspecialchars(strtolower($q['option_b'])) ?>"> b) <?= htmlspecialchars($q['option_b']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="<?= htmlspecialchars(strtolower($q['option_c'])) ?>"> c) <?= htmlspecialchars($q['option_c']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="<?= htmlspecialchars(strtolower($q['option_d'])) ?>"> d) <?= htmlspecialchars($q['option_d']) ?></label>
        <?php elseif ($type === 'fill'): ?>
          <input type="text" name="<?= $qid ?>" class="quiz-input" placeholder="Your answer">
        <?php endif; ?>

        <div class="feedback"></div>
      </div>
      <br>
      <?php
      $qNum++;
      endforeach;
      ?>
      <button type="button" class="btn" id="submitBtn">Submit</button>
      <div id="result"></div>
    </form>
  </div>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

<script src="/CyberDefend/js/ethical_quiz.js?v=1"></script>

</body>
</html>
