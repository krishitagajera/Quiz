<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// DB Connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Dynamic Stats
// 1. Total Users
$sql = "SELECT COUNT(*) AS total FROM users";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$totalUsers = $row['total'] ?? 0;

// 2. Total Quizzes = subjects
$sql = "SELECT COUNT(*) AS total FROM subjects";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$totalQuizzes = $row['total'] ?? 0;

// 3. Total Questions
$totalQuestions = 0;
$tables = ["questions", "questions_maths", "questions_science", "questions_history", "questions_geography", "questions_english"];
foreach ($tables as $t) {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM $t");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $totalQuestions += $row['cnt'];
    }
}

// 4. Total Attempts
$sql = "SELECT COUNT(*) AS total FROM attempts";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$totalAttempts = $row['total'] ?? 0;

// 5. Total Feedback
$sql = "SELECT COUNT(*) AS total FROM feedback";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);
$totalFeedback = $row['total'] ?? 0;

// Admin name
$admin = $_SESSION['admin'] ?? "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - QuizzyMind</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #00090aff, #2575fc);
  color: #fff;
  min-height: 100vh;
  display: flex;
  flex-direction: row;
}

/* Sidebar */
.sidebar {
  width: 240px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  border-radius: 0 20px 20px 0;
  padding: 30px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}
.sidebar img {
  width: 90px; height: 90px;
  border-radius: 50%; border: 3px solid #fff;
  margin-bottom: 15px;
}
.sidebar h2 { margin: 5px 0 20px; font-size: 18px; text-align: center; }
.menu { width: 100%; display: flex; flex-direction: column; gap: 15px; }
.menu a {
  text-decoration: none; color: #fff;
  padding: 12px; border-radius: 12px;
  text-align: center; background: rgba(255, 255, 255, 0.15);
  transition: 0.3s;
}
.menu a:hover { background: rgba(255, 255, 255, 0.35); }

/* Main */
.main { flex: 1; padding: 30px; display: flex; flex-direction: column; gap: 30px; }
.greeting { text-align: center; }
.greeting h1 { font-size: 28px; margin-bottom: 5px; }

/* Stats */
.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 20px;
}
.card {
  padding: 20px; border-radius: 16px; text-align: center;
  background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px);
  transition: 0.3s;
}
.card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.3); }

/* Options */
.options {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.row {
  display: flex;
  gap: 20px;
  justify-content: center;
}
.row .option-card {
  flex: 1;
  min-width: 220px;
}
.option-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  padding: 30px;
  border-radius: 20px;
  text-align: center;
  font-size: 18px;
  cursor: pointer;
  transition: 0.3s;
  text-decoration: none;
  color: #fff;
  height: 160px;
}
.option-card:hover { background: rgba(255, 255, 255, 0.3); transform: scale(1.05); }
.option-icon { font-size: 40px; margin-bottom: 10px; }
.option-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
.option-desc { font-size: 14px; opacity: 0.85; }

/* Responsive */
@media(max-width: 900px) {
  body { flex-direction: column; }
  .sidebar {
    width: 100%; border-radius: 0;
    flex-direction: row; justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
  }
  .sidebar h2 { display: none; }
  .main { padding: 15px; }
  .stats { grid-template-columns: 1fr 1fr; }
  .row { flex-direction: column; }
}
@media(max-width: 500px) {
  .stats { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="profile.jpg" alt="Admin">
    <h2><?= htmlspecialchars($admin) ?></h2>
    <div class="menu">
      <a href="admin_panel.php">🏠 Dashboard</a>
      <a href="add_question.php">➕ Add Question</a>
      <a href="view_users.php">👥 View Users</a>
      <a href="view_scores_admin.php">📊 View Scores</a>
      <a href="admin_feedback.php">💬 Feedback</a>
      <a href="admin_settings.php">⚙️ Settings</a>
      <a href="index.php?logout=true">🚪 Logout</a>
    </div>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="greeting">
      <h1>Welcome, <?= htmlspecialchars($admin) ?> 👋</h1>
      <p>Manage quizzes, users, results, and feedback efficiently.</p>
    </div>

    <!-- Stats -->
    <div class="stats">
      <div class="card"><h3>Total Users</h3><p><?= $totalUsers ?></p></div>
      <div class="card"><h3>Total Quizzes</h3><p><?= $totalQuizzes ?></p></div>
      <div class="card"><h3>Total Questions</h3><p><?= $totalQuestions ?></p></div>
      <div class="card"><h3>Total Attempts</h3><p><?= $totalAttempts ?></p></div>
      <div class="card"><h3>Feedback Received</h3><p><?= $totalFeedback ?></p></div>
    </div>

    <!-- Options -->
    <section class="options">
      <div class="row">
        <a href="add_question.php" class="option-card">
          <div class="option-icon">➕</div>
          <div class="option-title">Add New Question</div>
          <div class="option-desc">Create and manage quiz questions.</div>
        </a>

        <a href="view_users.php" class="option-card">
          <div class="option-icon">👥</div>
          <div class="option-title">View Users</div>
          <div class="option-desc">See registered users and their details.</div>
        </a>
      </div>

      <div class="row">
        <a href="view_scores_admin.php" class="option-card">
          <div class="option-icon">📊</div>
          <div class="option-title">View Scores</div>
          <div class="option-desc">Check user scores and quiz reports.</div>
        </a>

        <a href="admin_feedback.php" class="option-card">
          <div class="option-icon">💬</div>
          <div class="option-title">View Feedback</div>
          <div class="option-desc">Read feedback from users.</div>
        </a>
      </div>
    </section>
  </div>
</body>
</html>
