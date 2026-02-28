<?php
// session + DB
require_once __DIR__ . '/session_boot.php';
require_once 'db.php';

$topic_id = 1; // Network Security
$questions = [];

// Fetch questions
$stmt = $conn->prepare("
  SELECT id, question_type, question, option_a, option_b, option_c, option_d, correct_answer, explanation
  FROM quiz_questions
  WHERE topic_id = ?
  ORDER BY id ASC
");
$stmt->bind_param("i", $topic_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Network Security Quiz</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php include 'header.php'; ?>

<main>
  <div class="quiz-container" data-topic-id="<?= (int)$topic_id ?>">
    <h2>Network Security – Quick Quiz</h2>
    

    <noscript>
      JavaScript is required for this quiz. Please enable JavaScript and reload the page.
    </noscript>

    <?php if (empty($questions)): ?>
      <p><strong>No quiz questions available.</strong> Please contact the administrator.</p>
    <?php else: ?>
      <form id="quizForm" method="post" action="#">
        <?php
          $qNum = 1;
          foreach ($questions as $q):
            $qid   = 'q' . $qNum;
            $type  = $q['question_type'];
            // Safe attribute values
            $correctRaw  = strtolower(trim($q['correct_answer'] ?? ''));
            $correctAttr = htmlspecialchars($correctRaw, ENT_QUOTES);
            $explainAttr = htmlspecialchars($q['explanation'] ?? '', ENT_QUOTES);
            $qText       = htmlspecialchars($q['question'] ?? '', ENT_QUOTES);
            $a = htmlspecialchars($q['option_a'] ?? '', ENT_QUOTES);
            $b = htmlspecialchars($q['option_b'] ?? '', ENT_QUOTES);
            $c = htmlspecialchars($q['option_c'] ?? '', ENT_QUOTES);
            $d = htmlspecialchars($q['option_d'] ?? '', ENT_QUOTES);
        ?>
        <div class="question"
             data-correct-answer="<?= $correctAttr ?>"
             data-explanation="<?= $explainAttr ?>">
          <p><strong><?= $qNum ?>.</strong> <?= $qText ?></p>

          <?php if ($type === 'mcq'): ?>
            <label><input type="radio" name="<?= $qid ?>" value="a"> a) <?= $a ?></label><br>
            <label><input type="radio" name="<?= $qid ?>" value="b"> b) <?= $b ?></label><br>
            <label><input type="radio" name="<?= $qid ?>" value="c"> c) <?= $c ?></label><br>
            <label><input type="radio" name="<?= $qid ?>" value="d"> d) <?= $d ?></label>
          <?php elseif ($type === 'fill'): ?>
            <input type="text" name="<?= $qid ?>" class="quiz-input" placeholder="Your answer" />
            
          <?php else: ?>
            <div class="inline-note">Unsupported question type.</div>
          <?php endif; ?>

          <div class="feedback"></div>
        </div>
        <?php
            $qNum++;
          endforeach;
        ?>

        <button type="button" class="btn" id="submitBtn">Submit</button>
        <div id="result"></div>
      </form>
    <?php endif; ?>
  </div>
</main>

<footer>
  <a href="privacy.php">Privacy</a> |
  <a href="terms.php">Terms</a> |
  <a href="contact.php">Contact</a> |
  <a href="about.php">About</a>
</footer>

<!-- CSP-safe: external script from same origin -->
<script src="/CyberDefend/js/network_quiz.js?v=1"></script>
</body>
</html>
