<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Reset if restart requested
if (isset($_GET['restart'])) {
    unset($_SESSION['questions'], $_SESSION['current_q'], $_SESSION['score'], $_SESSION['subject'], $_SESSION['last_answer_correct']);
    header("Location: take_quiz.php");
    exit();
}


if (isset($_GET['subject'])) {
  $subject = strtolower(mysqli_real_escape_string($conn, $_GET['subject']));
  $table = "questions_" . $subject;

  // ✅ Check if table exists
  $checkTable = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
  if (mysqli_num_rows($checkTable) == 0) {
      die("Error: Question table '$table' does not exist in database.");
  }

  $res = mysqli_query($conn, "SELECT * FROM `$table` ORDER BY RAND() LIMIT 5");
  $_SESSION['questions'] = mysqli_fetch_all($res, MYSQLI_ASSOC);
  $_SESSION['subject']   = $subject;
  $_SESSION['current_q'] = 0;
  $_SESSION['score']     = 0;

  header("Location: take_quiz.php");
  exit();
}



// Show subject list if none chosen
if (!isset($_SESSION['subject'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Select Subject - QuizzyMind</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(#05000AFF, #2575fc);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .box {
                background: #fff;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                text-align: center;
            }
            h2 { margin-bottom: 20px; color: #333; }
            select, button {
                padding: 12px;
                font-size: 16px;
                margin: 10px 0;
                border-radius: 8px;
                border: 1px solid #ccc;
            }
            button {
                background: #007bff;
                color: #fff;
                border: none;
                cursor: pointer;
                transition: 0.3s;
            }
            button:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class="box">
            <h2>🎯 Choose a Subject</h2>
            <form method="GET">
                <select name="subject" required>
                    <option value="">-- Select Subject --</option>
                    <option value="english">English</option>
                    <option value="geography">Geography</option>
                    <option value="history">History</option>
                    <option value="maths">Maths</option>
                    <option value="science">Science</option>
                </select>
                <br>
                <button type="submit">Start Quiz</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Quiz progression
$questions = $_SESSION['questions'] ?? [];
$total_q   = count($questions);
$current_q = $_SESSION['current_q'] ?? 0;
$score     = $_SESSION['score'] ?? 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected = $_POST['answer'] ?? '';
    $correct  = $_POST['correct_answer'] ?? '';

    if ($selected === $correct) {
        $_SESSION['score']++;
        $_SESSION['last_answer_correct'] = true;
    } else {
        $_SESSION['last_answer_correct'] = false;
    }

    $_SESSION['current_q']++;

    if ($_SESSION['current_q'] >= $total_q) {
        header("Location: view_scores.php");
        exit();
    }

    $current_q = $_SESSION['current_q'];
    $score = $_SESSION['score'];
}

$questionData = $questions[$current_q] ?? null;
$progressPercent = ($total_q > 0) ? (($current_q + 1) / $total_q) * 100 : 0;
$lastCorrect = $_SESSION['last_answer_correct'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Take Quiz - QuizzyMind</title>
<style>
body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #05000AFF, #2575fc);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

/* Quiz Box */
.quiz-box {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  padding: 40px;
  border-radius: 20px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  border: 1px solid rgba(255,255,255,0.3);
  color: #fff;
  animation: fadeIn 0.8s ease-in-out;
}

h2 { 
  margin-bottom: 20px; 
  font-size: 24px; 
  text-align: center; 
  color: #fff; 
}

p { font-size: 18px; }

/* Options */
form { display: flex; flex-direction: column; gap: 12px; }
label {
  background: rgba(255, 255, 255, 0.2);
  padding: 14px;
  border-radius: 12px;
  cursor: pointer;
  border: 2px solid transparent;
  transition: 0.3s ease;
  font-size: 16px;
}
input[type="radio"] { margin-right: 10px; }
label:hover { background: rgba(255, 255, 255, 0.35); }

/* Buttons */
button {
  margin-top: 20px;
  padding: 14px;
  font-size: 16px;
  background: linear-gradient(90deg, #007bff, #00c6ff);
  color: #fff;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: transform 0.3s, box-shadow 0.3s;
}
button:hover { 
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* Restart link */
.restart {
  display: inline-block;
  margin-top: 15px;
  color: #fff;
  text-decoration: underline;
  font-size: 14px;
}
.restart:hover { color: #d4f1ff; }

/* Progress bar */
.progress-container {
  background: rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  overflow: hidden;
  height: 16px;
  margin-bottom: 15px;
}
.progress-bar {
  height: 100%;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  width: 0;
  transition: width 0.5s ease;
}
.progress-info {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 14px;
  color: #fff;
}

/* Score highlight */
.score {
  font-weight: bold;
  transition: color 0.5s ease, transform 0.3s ease;
}
.score.correct {
  color: #28a745;
  transform: scale(1.1);
}
.score.wrong {
  color: #ff4d4d;
  transform: scale(1.1);
}

/* Fade animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>
</head>
<body>

<div class="quiz-box">
  <h2>🧠 Quiz Time - <?= htmlspecialchars($_SESSION['subject']) ?></h2>

  <!-- Progress bar + score -->
  <div class="progress-info">
    <span>Question <?= $current_q + 1 ?> of <?= $total_q ?></span>
    <span class="score <?= ($lastCorrect === true ? 'correct' : ($lastCorrect === false ? 'wrong' : '')) ?>">
        Score: <?= $score ?>/<?= $total_q ?>
    </span>
  </div>
  <div class="progress-container">
    <div class="progress-bar" style="width: <?= $progressPercent ?>%;"></div>
  </div>

  <?php if ($questionData): ?>
    <form method="POST">
      <p><strong><?= htmlspecialchars($questionData['question']) ?></strong></p>

      <label><input type="radio" name="answer" value="<?= $questionData['option1'] ?>" required> <?= $questionData['option1'] ?></label>
      <label><input type="radio" name="answer" value="<?= $questionData['option2'] ?>"> <?= $questionData['option2'] ?></label>
      <label><input type="radio" name="answer" value="<?= $questionData['option3'] ?>"> <?= $questionData['option3'] ?></label>
      <label><input type="radio" name="answer" value="<?= $questionData['option4'] ?>"> <?= $questionData['option4'] ?></label>

      <input type="hidden" name="correct_answer" value="<?= $questionData['correct_answer'] ?>">

      <?php if ($current_q + 1 == $total_q): ?>
          <button type="submit">Submit Quiz</button>
      <?php else: ?>
          <button type="submit">Next</button>
      <?php endif; ?>
    </form>
  <?php else: ?>
    <p>No questions found for this subject.</p>
    <a href="take_quiz.php?restart=1" class="restart">Restart Quiz</a>
  <?php endif; ?>
</div>

</body>
</html>
