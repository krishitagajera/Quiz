<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user = $_SESSION['user'];

// Get attempt ID
$attempt_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch attempt
$sql = "SELECT attempts.*, subjects.name AS subject_name 
        FROM attempts 
        JOIN subjects ON attempts.subject_id = subjects.id 
        WHERE attempts.id = $attempt_id AND attempts.username = '$user'";

$attempt = mysqli_query($conn, $sql);
if (!$attempt || mysqli_num_rows($attempt) == 0) {
    die("❌ Attempt not found!");
}
$attempt = mysqli_fetch_assoc($attempt);

// Fetch answers
$sql = "SELECT qa.*, q.question, q.option_a, q.option_b, q.option_c, q.option_d, q.correct_answer 
        FROM attempt_answers qa
        JOIN questions_" . strtolower($attempt['subject_name']) . " q ON qa.question_id = q.id
        WHERE qa.attempt_id = $attempt_id";

$answers = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Attempt Details - QuizzyMind</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8f9fa;
      padding: 40px;
    }
    .container {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in-out;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
    }
    .score-box {
      text-align: center;
      font-size: 20px;
      margin-bottom: 30px;
      padding: 10px;
      background: #e9ecef;
      border-radius: 8px;
    }
    .question {
      margin-bottom: 20px;
      padding: 15px;
      border-left: 5px solid #007bff;
      background: #fdfdfd;
      border-radius: 8px;
      animation: slideUp 0.6s ease-in-out;
    }
    .options {
      margin-top: 10px;
    }
    .option {
      padding: 8px 12px;
      margin: 5px 0;
      border-radius: 6px;
    }
    .correct {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .wrong {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .user-choice {
      font-weight: bold;
    }
    a.back-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 16px;
      background: #007bff;
      color: white;
      border-radius: 6px;
      text-decoration: none;
    }
    a.back-btn:hover {
      background: #0056b3;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>📊 Attempt Details (<?= htmlspecialchars($attempt['subject_name']) ?>)</h2>

  <div class="score-box">
    Score: <b><?= $attempt['score'] ?>/<?= $attempt['total'] ?></b><br>
    Date: <?= date('d M Y, h:i A', strtotime($attempt['attempt_date'])) ?>
  </div>

  <?php while ($row = mysqli_fetch_assoc($answers)): ?>
    <div class="question">
      <p><b>Q<?= $row['question_id'] ?>:</b> <?= htmlspecialchars($row['question']) ?></p>
      <div class="options">
        <?php 
          $options = ['A' => $row['option_a'], 'B' => $row['option_b'], 'C' => $row['option_c'], 'D' => $row['option_d']];
          foreach ($options as $key => $val):
            $classes = '';
            if ($key == $row['correct_answer']) $classes .= ' correct';
            if ($key == $row['user_answer'] && $row['user_answer'] != $row['correct_answer']) $classes .= ' wrong user-choice';
        ?>
          <div class="option<?= $classes ?>">
            <?= $key ?>) <?= htmlspecialchars($val) ?>
            <?php if ($key == $row['user_answer']): ?> <span class="user-choice">← Your Answer</span> <?php endif; ?>
            <?php if ($key == $row['correct_answer']): ?> ✅ Correct <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endwhile; ?>

  <a href="attempt_history.php" class="back-btn">⬅ Back to History</a>
</div>

</body>
</html>
