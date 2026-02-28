<?php
require_once 'db.php';
$topic_id = 4; // Threat Analysis
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
  <title>Threat Analysis Quiz</title>
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
    <h2>Threat Analysis – Quick Quiz</h2>

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
          <label><input type="radio" name="<?= $qid ?>" value="a"> a) <?= htmlspecialchars($q['option_a']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="b"> b) <?= htmlspecialchars($q['option_b']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="c"> c) <?= htmlspecialchars($q['option_c']) ?></label><br>
          <label><input type="radio" name="<?= $qid ?>" value="d"> d) <?= htmlspecialchars($q['option_d']) ?></label>
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
      <button type="button" class="btn" onclick="checkAnswers()">Submit</button>
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

<script>
function checkAnswers() {
  const questions = document.querySelectorAll('.question');
  let score = 0;

  questions.forEach(question => {
    const correctAnswer = question.getAttribute('data-correct-answer').toLowerCase();
    const explanation = question.getAttribute('data-explanation');
    let userAnswer = "";

    const selectedOption = question.querySelector('input[type="radio"]:checked');
    const inputText = question.querySelector('input[type="text"]');

    if (selectedOption) {
      userAnswer = selectedOption.value.trim().toLowerCase(); // now A/B/C/D
    }

    if (inputText) {
      userAnswer = inputText.value.trim().toLowerCase();
    }

    const feedback = question.querySelector('.feedback');

    if (userAnswer === correctAnswer) {
      feedback.innerHTML = '<span class="correct">✅ Correct!</span>';
      score++;
    } else {
      feedback.innerHTML = `<span class="incorrect">❌ Incorrect.</span> <br><small>${explanation}</small>`;
    }
  });

  document.getElementById("result").innerText = `You got ${score} out of ${questions.length} correct!`;

  // Save score to database
  fetch('save_score.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
      topic_id: '4',
      type: 'quiz',
      score: score
    })
  })
  .then(response => response.text())
  .then(data => console.log(data))
  .catch(err => console.error('Error saving score:', err));

  document.querySelector('.btn').disabled = true;
}
</script>

</body>
</html>
