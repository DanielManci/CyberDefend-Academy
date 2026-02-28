<?php
session_start();
require_once 'db.php';

$feedback = [];
$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $score = 0;

    $answers = [
        'q1' => 'b',
        'q2' => 'rule-based',
        'q3' => 'c',
        'q4' => 'b',
        'q5' => 'password',
        'q6' => 'b',
        'q7' => 'show',
        'q8' => 'd',
        'q9' => 'c',
        'q10' => 'b'
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
        $topic = 'Ethical Hacking';
        $topicId = 2;
        $totalQuestions = count($answers);

        // Save to user_test_progress
        $stmt = $conn->prepare("
            INSERT INTO user_test_progress (user_id, topic_id, topic, score, total_questions, date_taken) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iisii", $userId, $topicId, $topic, $score, $totalQuestions);
        $stmt->execute();
        $stmt->close();

        // Save to user_scores (test only)
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
    <title>Ethical Hacking Test</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="content-container">
    <h2>Ethical Hacking – Test</h2>

    <?php if ($score !== null): ?>
        <div class="score-box" id="resultBox">
  <p id="resultText"><strong>You scored <?= $score; ?> out of 10.</strong></p>
  <a href="dashboard.php" class="btn">Return to Dashboard</a>
  <p style="margin-top: 10px; font-style: italic;">You will be redirected shortly...</p>
</div>

    <?php endif; ?>

    <form method="post">
        <div class="question">
            <p>1. Ethical hacking is also commonly known as:</p>
            <label><input type="radio" name="q1" value="a"> a) Black-hat hacking</label><br>
            <label><input type="radio" name="q1" value="b"> b) White-hat hacking</label><br>
            <label><input type="radio" name="q1" value="c"> c) Grey-hat hacking</label><br>
            <label><input type="radio" name="q1" value="d"> d) Red-hat hacking</label><br>
            <?php if (isset($feedback['q1']) && !$feedback['q1']): ?>
                <p class="feedback">❌ Ethical hackers are known as <strong>white-hat</strong> hackers.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>2. The technique that modifies known passwords using specific patterns is called a ______ attack.</p>
            <input type="text" name="q2">
            <?php if (isset($feedback['q2']) && !$feedback['q2']): ?>
                <p class="feedback">❌ This technique is called a <strong>rule-based</strong> attack.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>3. What command is used to install John the Ripper on a Linux system?</p>
            <label><input type="radio" name="q3" value="a"> a) sudo apt install ripper -y</label><br>
            <label><input type="radio" name="q3" value="b"> b) sudo apt-get john -y</label><br>
            <label><input type="radio" name="q3" value="c"> c) sudo apt install john -y</label><br>
            <label><input type="radio" name="q3" value="d"> d) sudo yum install john -y</label><br>
            <?php if (isset($feedback['q3']) && !$feedback['q3']): ?>
                <p class="feedback">❌ The correct command is <code>sudo apt install john -y</code>.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>4. Password spraying is a technique that:</p>
            <label><input type="radio" name="q4" value="a"> a) Attempts every possible password combination for a single account</label><br>
            <label><input type="radio" name="q4" value="b"> b) Tries common passwords across multiple accounts</label><br>
            <label><input type="radio" name="q4" value="c"> c) Uses wordlists combined with rules</label><br>
            <label><input type="radio" name="q4" value="d"> d) Guesses passwords based on leaked hashes</label><br>
            <?php if (isset($feedback['q4']) && !$feedback['q4']): ?>
                <p class="feedback">❌ Password spraying tries <strong>common passwords across many accounts</strong>.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>5. Companies often enforce their own ______ policies to prevent weak passwords.</p>
            <input type="text" name="q5">
            <?php if (isset($feedback['q5']) && !$feedback['q5']): ?>
                <p class="feedback">❌ The correct word is <strong>password</strong>.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>6. To crack a hashed password using John the Ripper with a wordlist, which command should you use?</p>
            <label><input type="radio" name="q6" value="a"> a) john --crack hashfile.txt</label><br>
            <label><input type="radio" name="q6" value="b"> b) john --wordlist=/usr/share/wordlists/rockyou.txt hashfile.txt</label><br>
            <label><input type="radio" name="q6" value="c"> c) john --hash hashfile.txt</label><br>
            <label><input type="radio" name="q6" value="d"> d) john --list=rockyou.txt hashfile.txt</label><br>
            <?php if (isset($feedback['q6']) && !$feedback['q6']): ?>
                <p class="feedback">❌ Use <code>john --wordlist=/usr/share/wordlists/rockyou.txt hashfile.txt</code> to crack a hash using a wordlist.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>7. To see cracked passwords in John the Ripper, use the command: john --______ hashfile.txt.</p>
            <input type="text" name="q7">
            <?php if (isset($feedback['q7']) && !$feedback['q7']): ?>
                <p class="feedback">❌ Use <code>john --show hashfile.txt</code> to display cracked passwords.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>8. Which of the following helps prevent brute-force attacks?</p>
            <label><input type="radio" name="q8" value="a"> a) Long, complex passwords</label><br>
            <label><input type="radio" name="q8" value="b"> b) Multi-Factor Authentication</label><br>
            <label><input type="radio" name="q8" value="c"> c) Limiting login attempts</label><br>
            <label><input type="radio" name="q8" value="d"> d) All of the above</label><br>
            <?php if (isset($feedback['q8']) && !$feedback['q8']): ?>
                <p class="feedback">❌ The correct answer is <strong>All of the above</strong>.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>9. What command is used to install John the Ripper on a Linux system?</p>
            <label><input type="radio" name="q9" value="a"> a) sudo apt install ripper -y</label><br>
            <label><input type="radio" name="q9" value="b"> b) sudo apt-get john -y</label><br>
            <label><input type="radio" name="q9" value="c"> c) sudo apt install john -y</label><br>
            <label><input type="radio" name="q9" value="d"> d) sudo yum install john -y</label><br>
            <?php if (isset($feedback['q9']) && !$feedback['q9']): ?>
                <p class="feedback">❌ The correct installation command is <strong>sudo apt install john -y</strong>.</p>
            <?php endif; ?>
        </div>

        <div class="question">
            <p>10. What does MFA stand for in cybersecurity?</p>
            <label><input type="radio" name="q10" value="a"> a) Multiple File Access</label><br>
            <label><input type="radio" name="q10" value="b"> b) Multi-Factor Authentication</label><br>
            <label><input type="radio" name="q10" value="c"> c) Malicious File Analyzer</label><br>
            <label><input type="radio" name="q10" value="d"> d) Managed Firewall Access</label><br>
            <?php if (isset($feedback['q10']) && !$feedback['q10']): ?>
                <p class="feedback">❌ MFA stands for <strong>Multi-Factor Authentication</strong>.</p>
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
