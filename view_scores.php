<?php
session_start();

// Ensure quiz session exists
if (!isset($_SESSION['user']) || !isset($_SESSION['score']) || !isset($_SESSION['questions']) || !isset($_SESSION['subject'])) {
    die("<h2 style='font-family: Arial; text-align:center; margin-top:50px;'>⚠️ No quiz attempt found.<br><a href='take_quiz.php'>Start a Quiz</a></h2>");
}

$score    = (int)$_SESSION['score'];
$total    = count($_SESSION['questions']);
$subject  = $_SESSION['subject'];
$username = $_SESSION['user'];

// DB connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// Get subject_id from subjects table
$stmt = $conn->prepare("SELECT id, name FROM subjects WHERE LOWER(name) = ?");
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$subject_id = $row['id'] ?? null;
$subjectName = ucfirst($row['name'] ?? $subject);
$stmt->close();

// Save attempt
if ($subject_id) {
    $insert = $conn->prepare("INSERT INTO attempts (user, subject_id, score, total) VALUES (?, ?, ?, ?)");
    $insert->bind_param("siii", $username, $subject_id, $score, $total);
    $insert->execute();
    $insert->close();
}

// Clear quiz progress session (but keep user logged in)
unset($_SESSION['questions'], $_SESSION['current_q'], $_SESSION['score'], $_SESSION['subject'], $_SESSION['last_answer_correct']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Quiz Results - QuizzyMind</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #00090aff, #2575fc);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  margin: 0;
  overflow: hidden;
}

/* Confetti pieces */
.confetti-piece {
  position: absolute;
  width: 8px;
  height: 8px;
  background-color: #00f9ff;
  opacity: 0.8;
  border-radius: 50%;
  animation: confettiFall linear infinite;
}
@keyframes confettiFall { 
  0% { transform: translateY(-10px) rotate(0deg); opacity:1; } 
  100% { transform: translateY(800px) rotate(360deg); opacity:0; } 
}

.result-box {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(15px);
  padding: 40px;
  border-radius: 25px;
  text-align: center;
  box-shadow: 0 0 40px rgba(0,255,255,0.5);
  width: 420px;
  color: #fff;
  border: 2px solid rgba(255, 255, 255, 0.25);
  transform: scale(0);
  opacity: 0;
  animation: popIn 0.8s forwards;
  position: relative;
  overflow: hidden;
}

h2 {
  margin-bottom: 20px;
  font-size: 28px;
  font-weight: 600;
  text-shadow: 0 0 10px #00f9ff, 0 0 20px #0072ff;
}

p {
  font-size: 18px;
  margin: 12px 0;
}

/* Glowing score & subject */
.glow {
  font-weight: 700;
  color: #00ffff;
  text-shadow: 0 0 10px #00f9ff, 0 0 20px #0072ff, 0 0 30px #00f9ff;
  animation: pulseGlow 1.5s infinite alternate;
  display: inline-block;
  position: relative;
}

@keyframes pulseGlow {
  0% { text-shadow: 0 0 10px #00f9ff,0 0 20px #0072ff,0 0 30px #00f9ff; transform: scale(1); }
  50% { text-shadow: 0 0 20px #00f9ff,0 0 40px #0072ff,0 0 60px #00f9ff; transform: scale(1.1); }
  100% { text-shadow: 0 0 10px #00f9ff,0 0 20px #0072ff,0 0 30px #00f9ff; transform: scale(1); }
}

/* Sparkle stars */
.sparkle {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
  box-shadow: 0 0 6px #00f9ff, 0 0 10px #0072ff;
  opacity: 0;
  animation: sparkleAnim linear forwards;
}
@keyframes sparkleAnim {
  0% { transform: translate(0,0) scale(0); opacity: 1; }
  50% { opacity:1; }
  100% { transform: translate(var(--dx), var(--dy)) scale(0); opacity:0; }
}

.btn {
  display: inline-block;
  margin: 12px 6px 0 6px;
  padding: 12px 26px;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  color: #fff;
  text-decoration: none;
  border-radius: 40px;
  font-size: 16px;
  font-weight: 600;
  box-shadow: 0 6px 15px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}
.btn:hover {
  transform: translateY(-4px) scale(1.05);
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  box-shadow: 0 8px 20px rgba(0,0,0,0.35);
}

@keyframes popIn {
  0% { transform: scale(0); opacity: 0; }
  60% { transform: scale(1.2); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}

@media(max-width:500px) {
  .result-box { width: 90%; padding: 25px; }
  h2 { font-size: 22px; }
  p { font-size: 16px; }
  .btn { font-size: 14px; padding: 10px 20px; }
}
</style>
</head>
<body>
<div class="result-box" id="resultBox">
  <h2>✅ Quiz Finished</h2>
  <p><strong>Subject:</strong> <span class="glow" id="subjectGlow"><?= htmlspecialchars($subjectName) ?></span></p>
  <p><strong>Your Score:</strong> <span class="glow" id="scoreGlow"><?= $score ?> / <?= $total ?></span></p>
  <a href="take_quiz.php?subject=<?= strtolower($subjectName) ?>" class="btn">Try Again</a>
  <a href="subjects.php" class="btn">Choose Another Subject</a>
  <a href="attempt_history.php" class="btn">View History</a>
  <a href="certificate.php?user=<?= urlencode($username) ?>&subject=<?= urlencode($subjectName) ?>&score=<?= $score ?>&total=<?= $total ?>" class="btn">View Certificate</a>
</div>


</body>
</html>
