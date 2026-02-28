<?php
require_once __DIR__ . '/session_boot.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/csrf.php';

$updateType = isset($_GET['update']) ? strtolower($_GET['update']) : '';
$updateId   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$showUpdateTopic = ($updateType === 'topic' && $updateId > 0);
$showUpdateQuiz  = ($updateType === 'quiz'  && $updateId > 0);
$showUpdateTest  = ($updateType === 'test'  && $updateId > 0);

$showSavedBanner = (isset($_GET['saved']) && $_GET['saved'] == '1');

// POST → GET routing to keep section state after select dropdowns
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check(); // validate token for any POST hitting this page

    if (isset($_POST['test_question_id'])) {
        $id = (int)$_POST['test_question_id'];
        header("Location: admin_dashboard.php?update=test&id={$id}#update-test");
        exit;
    }
    if (isset($_POST['quiz_question_id'])) {
        $id = (int)$_POST['quiz_question_id'];
        header("Location: admin_dashboard.php?update=quiz&id={$id}#update-quiz");
        exit;
    }
    if (isset($_POST['update_topic_id'])) {
        $id = (int)$_POST['update_topic_id'];
        header("Location: admin_dashboard.php?update=topic&id={$id}#update-topic");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - CyberDefend Academy</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body { margin:0; }
    h1 { text-align:center; margin:16px 0; }
    .crud-buttons { text-align:center; margin-top: 24px; }
    .crud-buttons button { margin: 10px; padding: 12px 24px; font-size: 18px; cursor: pointer; border-radius:8px; }
    .modal-bg { display:none; position: fixed; inset:0; background: rgba(0,0,0,.6); justify-content:center; align-items:center; }
    .modal { background:#fff; padding:30px; border-radius:12px; min-width:320px; text-align:center; box-shadow:0 20px 60px rgba(0,0,0,.35); }
    .modal select, .modal button { margin-top: 12px; padding: 10px; font-size:16px; width: 100%; }
    .section { display:none; padding: 20px; margin: 30px auto; width: 85%; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.15); }
    .section h3 { margin-top:0; }
    .banner { margin: 12px auto; width: 85%; background:#e6ffed; color:#0a5; border:1px solid #a4e8bf; padding:10px 14px; border-radius:8px; }
    .error { color:#a00; }
    table { width:100%; border-collapse:collapse; }
    th, td { border-bottom: 1px solid #eee; padding: 8px; text-align:left; vertical-align:top; }
  </style>
</head>
<body>

<?php if ($showSavedBanner): ?>
  <div class="banner">Changes saved successfully.</div>
<?php endif; ?>

<h1>Admin Dashboard</h1>

<div class="crud-buttons">
  <button type="button" onclick="openModal('Insert')">Insert</button>
  <button type="button" onclick="openModal('Update')">Update</button>
  <button type="button" onclick="openModal('Delete')">Delete</button>
  <button type="button" onclick="openModal('View')">View</button>
</div>

<!-- Modal chooser -->
<div class="modal-bg" id="modalBg">
  <div class="modal">
    <h2 id="modalTitle">What do you want to manage?</h2>
    <select id="contentType">
      <option value="topics">Topics</option>
      <option value="quiz">Quiz Questions</option>
      <option value="test">Test Questions</option>
      <option value="progress" id="progressOption">User Progress</option>
    </select>
    <button type="button" onclick="showSection()">Continue</button>
    <button type="button" onclick="closeModal()">Cancel</button>
  </div>
</div>

<!-- ========================= INSERT ========================= -->
<div id="insert-topics" class="section">
  <h3>Insert Topic</h3>
  <form action="admin_insert_topic.php" method="POST" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>
    <label for="title"><strong>Topic Name:</strong></label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="short_description"><strong>Subtitle (one line):</strong></label><br>
    <input type="text" id="short_description" name="short_description" maxlength="255"><br><br>

    <label for="description"><strong>Topic Content:</strong></label><br>
    <textarea id="description" name="description" rows="10" required></textarea><br><br>

    <label for="video_link"><strong>Video Link (optional):</strong></label><br>
    <input type="url" id="video_link" name="video_link"><br><br>

    <button type="submit">Add Topic</button>
  </form>
</div>

<div id="insert-quiz" class="section">
  <h3>Insert Quiz Question</h3>
  <form action="admin_insert_quiz.php" method="POST" id="quizForm" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>

    <label for="quiz_topic_select">Select Topic:</label><br>
    <select name="topic_id" id="quiz_topic_select" required>
      <option value="">-- Choose Topic --</option>
      <?php
        $result = $conn->query("SELECT id, title FROM topics ORDER BY id ASC");
        while ($row = $result->fetch_assoc()) {
          echo '<option value="'.(int)$row['id'].'">'.htmlspecialchars($row['title']).'</option>';
        }
      ?>
    </select><br><br>

    <label for="question_type">Question Type:</label><br>
    <select name="question_type" id="question_type" onchange="toggleQuestionType()" required>
      <option value="mcq">Multiple Choice</option>
      <option value="fill">Fill in the Blank</option>
    </select><br><br>

    <label for="question">Question:</label><br>
    <textarea name="question" id="question" rows="4" required></textarea><br><br>

    <div id="mcq_options">
      <label>Option A:</label><br><input type="text" name="option_a"><br>
      <label>Option B:</label><br><input type="text" name="option_b"><br>
      <label>Option C:</label><br><input type="text" name="option_c"><br>
      <label>Option D:</label><br><input type="text" name="option_d"><br><br>

      <label for="correct_answer">Correct Option:</label><br>
      <select name="correct_answer" id="correct_answer">
        <option value="">-- Choose --</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select><br><br>
    </div>

    <div id="fill_answer" style="display:none;">
      <label for="correct_answer_fill">Correct Answer:</label><br>
      <input type="text" name="correct_answer_fill"><br><br>
    </div>

    <button type="submit">Add Quiz Question</button>
  </form>
</div>

<div id="insert-test" class="section">
  <h3>Insert Test Question</h3>
  <form action="admin_insert_test.php" method="POST" id="testForm" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>

    <label for="test_topic_select">Select Topic:</label><br>
    <select name="topic_id" id="test_topic_select" required>
      <option value="">-- Choose Topic --</option>
      <?php
        $result = $conn->query("SELECT id, title FROM topics ORDER BY id ASC");
        while ($row = $result->fetch_assoc()) {
          echo '<option value="'.(int)$row['id'].'">'.htmlspecialchars($row['title']).'</option>';
        }
      ?>
    </select><br><br>

    <label for="test_question_type">Question Type:</label><br>
    <select name="question_type" id="test_question_type" onchange="toggleTestType()" required>
      <option value="mcq">Multiple Choice</option>
      <option value="fill">Fill in the Blank</option>
    </select><br><br>

    <label for="test_question">Question:</label><br>
    <textarea name="question" id="test_question" rows="4" required></textarea><br><br>

    <div id="test_mcq_options">
      <label>Option A:</label><br><input type="text" name="option_a"><br>
      <label>Option B:</label><br><input type="text" name="option_b"><br>
      <label>Option C:</label><br><input type="text" name="option_c"><br>
      <label>Option D:</label><br><input type="text" name="option_d"><br><br>

      <label for="test_correct_answer">Correct Option:</label><br>
      <select name="correct_answer" id="test_correct_answer">
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
      </select><br><br>
    </div>

    <div id="test_fill_answer" style="display:none;">
      <label for="correct_answer_fill">Correct Answer:</label><br>
      <input type="text" name="correct_answer_fill"><br><br>
    </div>

    <button type="submit">Add Test Question</button>
  </form>
</div>

<!-- ========================= UPDATE TOPIC ========================= -->
<div id="update-topic" class="section" style="display: <?= $showUpdateTopic ? 'block' : 'none' ?>;">
  <h3>Update Topic</h3>

  <label for="update_topic_id">Select a topic to update:</label><br>
  <form method="POST" style="margin:0;padding:0;display:inline;">
    <?= csrf_field() ?>
    <select name="update_topic_id" id="update_topic_id" onchange="this.form.submit()" required>
      <option value="">-- Choose Topic --</option>
      <?php
        $result = $conn->query("SELECT id, title FROM topics ORDER BY id ASC");
        $selectedId = ($showUpdateTopic ? $updateId : 0);
        while ($row = $result->fetch_assoc()):
          $id  = (int)$row['id'];
          $sel = $id === $selectedId ? ' selected' : '';
      ?>
        <option value="<?= $id ?>"<?= $sel ?>><?= htmlspecialchars($row['title']) ?></option>
      <?php endwhile; ?>
    </select>
  </form>

  <?php
  if ($showUpdateTopic) {
      $stmt = $conn->prepare("SELECT id, title, short_description, description, video_link FROM topics WHERE id = ? LIMIT 1");
      $stmt->bind_param("i", $updateId);
      $stmt->execute();
      $res = $stmt->get_result();
      if ($res && $res->num_rows === 1):
          $topic = $res->fetch_assoc();
  ?>
  <form method="POST" action="admin_update_topic.php" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>
    <input type="hidden" name="topic_id" value="<?= (int)$topic['id'] ?>">

    <label>Topic Title:</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($topic['title']) ?>" required><br><br>

    <label>Subtitle (one line):</label><br>
    <input type="text" name="short_description" maxlength="255" value="<?= htmlspecialchars($topic['short_description'] ?? '') ?>"><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="8" required><?= htmlspecialchars($topic['description']) ?></textarea><br><br>

    <label>Video Link:</label><br>
    <input type="url" name="video_link" value="<?= htmlspecialchars($topic['video_link']) ?>"><br><br>

    <button type="submit">Update Topic</button>
  </form>
  <?php else: ?>
    <p class="error">Selected topic not found.</p>
  <?php
      endif;
      $stmt->close();
  }
  ?>
</div>

<div id="update-quiz" class="section">
  <h3>Update Quiz Question</h3>
  <form method="POST" style="margin:0;padding:0;display:inline;">
    <?= csrf_field() ?>
    <label for="quiz_question_id">Choose a question to update:</label><br>
    <select name="quiz_question_id" id="quiz_question_id" onchange="this.form.submit()" required>
      <option value="">-- Choose Question --</option>
      <?php
        $q = $conn->query("SELECT qq.id, t.title AS topic_title, qq.question FROM quiz_questions qq JOIN topics t ON qq.topic_id=t.id ORDER BY t.id, qq.id");
        $curr = ($showUpdateQuiz ? $updateId : 0);
        while ($row = $q->fetch_assoc()) {
          $short = htmlspecialchars(substr($row['question'],0,50));
          $sel = $curr === (int)$row['id'] ? ' selected' : '';
          echo '<option value="'.(int)$row['id'].'"'.$sel.'>'.htmlspecialchars($row['topic_title']).' – '.$short.'...</option>';
        }
      ?>
    </select>
  </form>
  <?php
    if ($showUpdateQuiz) {
      $id = $updateId;
      $st = $conn->prepare("SELECT * FROM quiz_questions WHERE id=?");
      $st->bind_param('i',$id); $st->execute(); $r=$st->get_result();
      if ($r && $r->num_rows>0) { $question = $r->fetch_assoc(); ?>
  <form method="POST" action="admin_update_quiz.php" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>
    <input type="hidden" name="question_id" value="<?= (int)$question['id'] ?>">

    <label>Question Type:</label><br>
    <select name="question_type" id="quiz_update_type" onchange="toggleQuizUpdateType()" required>
      <option value="mcq" <?= $question['question_type']==='mcq'?'selected':'' ?>>Multiple Choice</option>
      <option value="fill" <?= $question['question_type']==='fill'?'selected':'' ?>>Fill in the Blank</option>
    </select><br><br>

    <label>Question:</label><br>
    <textarea name="question" rows="4" required><?= htmlspecialchars($question['question']) ?></textarea><br><br>

    <div id="quiz_update_mcq" style="display: <?= $question['question_type']==='mcq'?'block':'none' ?>;">
      <label>Option A:</label><br><input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>"><br>
      <label>Option B:</label><br><input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>"><br>
      <label>Option C:</label><br><input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>"><br>
      <label>Option D:</label><br><input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>"><br><br>
      <label>Correct Option:</label><br>
      <select name="correct_answer" id="quiz_correct_option">
        <option value="A" <?= $question['correct_answer']==='A'?'selected':'' ?>>A</option>
        <option value="B" <?= $question['correct_answer']==='B'?'selected':'' ?>>B</option>
        <option value="C" <?= $question['correct_answer']==='C'?'selected':'' ?>>C</option>
        <option value="D" <?= $question['correct_answer']==='D'?'selected':'' ?>>D</option>
      </select><br><br>
    </div>

    <div id="quiz_update_fill" style="display: <?= $question['question_type']==='fill'?'block':'none' ?>;">
      <label>Correct Answer:</label><br>
      <input type="text" name="correct_answer_fill" value="<?= htmlspecialchars($question['correct_answer']) ?>">
      <br><br>
    </div>

    <button type="submit">Update Quiz Question</button>
  </form>
  <?php } } ?>
</div>

<div id="update-test" class="section">
  <h3>Update Test Question</h3>
  <form method="POST" style="margin:0;padding:0;display:inline;">
    <?= csrf_field() ?>
    <label for="test_question_id">Choose a question to update:</label><br>
    <select name="test_question_id" id="test_question_id" onchange="this.form.submit()" required>
      <option value="">-- Choose Question --</option>
      <?php
        $q = $conn->query("SELECT tq.id, t.title AS topic_title, tq.question FROM test_questions tq JOIN topics t ON tq.topic_id=t.id ORDER BY t.id, tq.id");
        $curr = ($showUpdateTest ? $updateId : 0);
        while ($row = $q->fetch_assoc()) {
          $short = htmlspecialchars(substr($row['question'],0,50));
          $sel = $curr === (int)$row['id'] ? ' selected' : '';
          echo '<option value="'.(int)$row['id'].'"'.$sel.'>'.htmlspecialchars($row['topic_title']).' – '.$short.'...</option>';
        }
      ?>
    </select>
  </form>
  <?php
    if ($showUpdateTest) {
      $id = $updateId;
      $st = $conn->prepare("SELECT * FROM test_questions WHERE id=?");
      $st->bind_param('i',$id); $st->execute(); $r=$st->get_result();
      if ($r && $r->num_rows>0) { $question = $r->fetch_assoc(); ?>
  <form method="POST" action="admin_update_test.php" onsubmit="return lockOnce(this)">
    <?= csrf_field() ?>
    <input type="hidden" name="question_id" value="<?= (int)$question['id'] ?>">

    <label>Question Type:</label><br>
    <select name="question_type" id="test_update_type" onchange="toggleTestUpdateType()" required>
      <option value="mcq" <?= $question['question_type']==='mcq'?'selected':'' ?>>Multiple Choice</option>
      <option value="fill" <?= $question['question_type']==='fill'?'selected':'' ?>>Fill in the Blank</option>
    </select><br><br>

    <label>Question:</label><br>
    <textarea name="question" rows="4" required><?= htmlspecialchars($question['question']) ?></textarea><br><br>

    <div id="test_update_mcq" style="display: <?= $question['question_type']==='mcq'?'block':'none' ?>;">
      <label>Option A:</label><br><input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>"><br>
      <label>Option B:</label><br><input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>"><br>
      <label>Option C:</label><br><input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>"><br>
      <label>Option D:</label><br><input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>"><br><br>
      <label>Correct Option:</label><br>
      <select name="correct_answer" id="test_correct_option_edit">
        <option value="A" <?= $question['correct_answer']==='A'?'selected':'' ?>>A</option>
        <option value="B" <?= $question['correct_answer']==='B'?'selected':'' ?>>B</option>
        <option value="C" <?= $question['correct_answer']==='C'?'selected':'' ?>>C</option>
        <option value="D" <?= $question['correct_answer']==='D'?'selected':'' ?>>D</option>
      </select><br><br>
    </div>

    <div id="test_update_fill" style="display: <?= $question['question_type']==='fill'?'block':'none' ?>;">
      <label>Correct Answer:</label><br>
      <input type="text" name="correct_answer_fill" value="<?= htmlspecialchars($question['correct_answer']) ?>"><br><br>
    </div>

    <button type="submit">Update Test Question</button>
  </form>
  <?php } } ?>
</div>

<!-- ========================= DELETE ========================= -->
<div id="delete-topics" class="section">
  <h3>Delete Topic</h3>
  <form action="admin_delete_topic.php" method="POST" onsubmit="return confirm('Delete this topic?')">
    <?= csrf_field() ?>
    <label for="del_topic_id">Select a topic to delete:</label><br>
    <select name="topic_id" id="del_topic_id" required>
      <option value="">-- Choose Topic --</option>
      <?php
        $result = $conn->query("SELECT id, title FROM topics ORDER BY id ASC");
        while ($row = $result->fetch_assoc()) {
          echo '<option value="'.(int)$row['id'].'">'.htmlspecialchars($row['title']).'</option>';
        }
      ?>
    </select><br><br>
    <button type="submit">Delete Topic</button>
  </form>
</div>

<div id="delete-quiz" class="section">
  <h3>Delete Quiz Question</h3>
  <form method="POST" action="admin_delete_quiz.php" onsubmit="return confirm('Delete this quiz question?')">
    <?= csrf_field() ?>
    <label for="del_quiz_id">Select a quiz question to delete:</label><br>
    <select name="question_id" id="del_quiz_id" required>
      <option value="">-- Choose Question --</option>
      <?php
        $q = $conn->query("SELECT qq.id, t.title AS topic_title, qq.question FROM quiz_questions qq JOIN topics t ON qq.topic_id=t.id ORDER BY t.id, qq.id");
        while ($row = $q->fetch_assoc()) {
          $display = htmlspecialchars($row['topic_title']).' - '.htmlspecialchars(substr($row['question'],0,50)).'...';
          echo '<option value="'.(int)$row['id'].'">'.$display.'</option>';
        }
      ?>
    </select><br><br>
    <button type="submit">Delete Question</button>
  </form>
</div>

<div id="delete-test" class="section">
  <h3>Delete Test Question</h3>
  <form method="POST" action="admin_delete_test.php" onsubmit="return confirm('Delete this test question?')">
    <?= csrf_field() ?>
    <label for="del_test_id">Select a test question to delete:</label><br>
    <select name="question_id" id="del_test_id" required>
      <option value="">-- Choose Question --</option>
      <?php
        $q = $conn->query("SELECT tq.id, t.title AS topic_title, tq.question FROM test_questions tq JOIN topics t ON tq.topic_id=t.id ORDER BY t.id, tq.id");
        while ($row = $q->fetch_assoc()) {
          $display = htmlspecialchars($row['topic_title']).' - '.htmlspecialchars(substr($row['question'],0,50)).'...';
          echo '<option value="'.(int)$row['id'].'">'.$display.'</option>';
        }
      ?>
    </select><br><br>
    <button type="submit">Delete Question</button>
  </form>
</div>

<!-- ========================= VIEW ========================= -->
<div id="view-topics" class="section">
  <h3>View Topics</h3>
  <?php
    $result = $conn->query("SELECT * FROM topics ORDER BY id ASC");
    if ($result && $result->num_rows>0): ?>
      <table>
        <tr>
          <th>ID</th><th>Title</th><th>Subtitle</th><th>Description</th><th>Video Link</th>
        </tr>
        <?php while($row=$result->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['short_description'] ?? '') ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
            <td><?= htmlspecialchars($row['video_link']) ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
  <?php else: ?>
    <p>No topics found.</p>
  <?php endif; ?>
</div>

<div id="view-quiz" class="section">
  <h3>View Quiz Questions</h3>
  <?php
    $r=$conn->query("SELECT qq.*, t.title AS topic_title FROM quiz_questions qq JOIN topics t ON qq.topic_id=t.id ORDER BY qq.topic_id, qq.id");
    if ($r && $r->num_rows>0): ?>
      <table>
        <tr>
          <th>ID</th><th>Topic</th><th>Type</th><th>Question</th>
          <th>Option A</th><th>Option B</th><th>Option C</th><th>Option D</th>
          <th>Correct Answer</th>
        </tr>
        <?php while($row=$r->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['topic_title']) ?></td>
            <td><?= htmlspecialchars($row['question_type']) ?></td>
            <td><?= htmlspecialchars($row['question']) ?></td>
            <td><?= htmlspecialchars($row['option_a'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_b'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_c'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_d'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['correct_answer'] ?? '') ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
  <?php else: ?>
    <p>No quiz questions found.</p>
  <?php endif; ?>
</div>

<div id="view-test" class="section">
  <h3>View Test Questions</h3>
  <?php
    $r=$conn->query("SELECT tq.*, t.title AS topic_title FROM test_questions tq JOIN topics t ON tq.topic_id=t.id ORDER BY tq.topic_id, tq.id");
    if ($r && $r->num_rows>0): ?>
      <table>
        <tr>
          <th>ID</th><th>Topic</th><th>Type</th><th>Question</th>
          <th>Option A</th><th>Option B</th><th>Option C</th><th>Option D</th>
          <th>Correct Answer</th>
        </tr>
        <?php while($row=$r->fetch_assoc()): ?>
          <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['topic_title']) ?></td>
            <td><?= htmlspecialchars($row['question_type']) ?></td>
            <td><?= htmlspecialchars($row['question']) ?></td>
            <td><?= htmlspecialchars($row['option_a'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_b'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_c'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['option_d'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['correct_answer'] ?? '') ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
  <?php else: ?>
    <p>No test questions found.</p>
  <?php endif; ?>
</div>

<div id="view-progress" class="section">
  <h3>View User Progress</h3>
  <?php
    $cols=[]; $c=$conn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'user_test_progress'");
    if ($c) while($x=$c->fetch_assoc()) $cols[$x['COLUMN_NAME']]=true;
    $scoreExpr = isset($cols['score']) ? 'p.score' : (isset($cols['test_score']) ? 'p.test_score AS score' : 'COALESCE(p.quiz_score,0)+COALESCE(p.test_score,0) AS score');
    $sql = "SELECT u.username, t.title AS topic_title, $scoreExpr
            FROM user_test_progress p
            JOIN users u ON p.user_id=u.id
            JOIN topics t ON p.topic_id=t.id
            ORDER BY u.username, t.id";
    $r=$conn->query($sql);
    if ($r && $r->num_rows>0): ?>
      <table>
        <tr><th>Username</th><th>Topic</th><th>Score</th></tr>
        <?php while($row=$r->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['topic_title']) ?></td>
            <td><?= htmlspecialchars($row['score']) ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>No progress records found.</p>
    <?php endif; ?>
</div>

<script>
// Crash guard to surface any remaining JS errors
window.onerror = function (msg, src, line, col) {
  console.log('JS error:', msg, 'at', src + ':' + line + ':' + col);
};

var currentAction = '';
function $(id){ return document.getElementById(id); }
function show(el){ if (el) el.style.display = 'block'; }
function hide(el){ if (el) el.style.display = 'none'; }

// Modal open/close (single source of truth)
function openModal(action){
  currentAction = (action || '').toLowerCase();
  var title = $('modalTitle');
  if (title) title.innerText = 'What do you want to ' + currentAction + '?';
  var progressOpt = $('progressOption');
  if (progressOpt) progressOpt.style.display = (currentAction === 'view') ? 'block' : 'none';
  var bg = $('modalBg');
  if (bg) { bg.style.display = 'flex'; }
}
function closeModal(){
  var bg = $('modalBg');
  if (bg) { bg.style.display = 'none'; }
}

// Section routing from modal
function showSection(){
  var typeSel = $('contentType');
  var type = typeSel ? typeSel.value : '';

  // hide all
  document.querySelectorAll('.section').forEach(function(el){ el.style.display = 'none'; });

  var sectionId = currentAction + '-' + type;
  if (currentAction === 'update' && type === 'topics') sectionId = 'update-topic';
  var section = $(sectionId);
  if (section) {
    section.style.display = 'block';
    window.location.hash = sectionId;
  }
  closeModal();
}

// Toggle helpers
function toggleQuestionType(){
  var t = $('question_type').value;
  show($('mcq_options')); hide($('fill_answer'));
  if (t === 'fill'){ hide($('mcq_options')); show($('fill_answer')); }
}
function toggleTestType(){
  var t = $('test_question_type').value;
  show($('test_mcq_options')); hide($('test_fill_answer'));
  if (t === 'fill'){ hide($('test_mcq_options')); show($('test_fill_answer')); }
}
function toggleQuizUpdateType(){
  var t = $('quiz_update_type').value;
  show($('quiz_update_mcq')); hide($('quiz_update_fill'));
  if (t === 'fill'){ hide($('quiz_update_mcq')); show($('quiz_update_fill')); }
}
function toggleTestUpdateType(){
  var t = $('test_update_type').value;
  show($('test_update_mcq')); hide($('test_update_fill'));
  if (t === 'fill'){ hide($('test_update_mcq')); show($('test_update_fill')); }
}

// Topic dropdown → GET navigation
function bindTopicSelectHandler(){
  var sel = $('update_topic_id');
  if (!sel) return;
  sel.onchange = function () {
    if (!this.value) return;
    window.location.href = 'admin_dashboard.php?update=topic&id=' + encodeURIComponent(this.value) + '#update-topic';
  };
}

// Initial deep-link handling
window.addEventListener('DOMContentLoaded', function(){
  var hash = window.location.hash ? window.location.hash.substring(1) : '';
  if (hash) {
    document.querySelectorAll('.section').forEach(function(el){ el.style.display = 'none'; });
    var sec = $(hash);
    if (sec) sec.style.display = 'block';
  }

  try {
    var url = new URL(window.location);
    var updateType = url.searchParams.get('update');
    var id = url.searchParams.get('id');
    if (updateType && id) {
      var sectionId = (updateType === 'topic') ? 'update-topic' : ('update-' + updateType);
      var sec2 = $(sectionId);
      if (sec2) {
        sec2.style.display = 'block';
        window.location.hash = sectionId;
      }
    }
    if (window.location.hash.indexOf('#view-') === 0) {
      url.searchParams.delete('update'); url.searchParams.delete('id');
      window.history.replaceState({}, document.title, url.toString());
    }
  } catch(e) {}

  bindTopicSelectHandler();
});

// Double-submit guard that never blocks submission
function lockOnce(form){
  if (form.dataset.locked) return true;
  form.dataset.locked = "1";
  var btn = form.querySelector('button[type="submit"]');
  if (btn){ btn.disabled = true; btn.textContent = 'Processing...'; }
  return true;
}
</script>
</body>
</html>
