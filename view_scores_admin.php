<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Corrected JOIN
$sql = "
    SELECT a.id, u.username, s.name AS subject, a.score, a.total, a.attempt_date
    FROM attempts a
    LEFT JOIN users u ON a.user = u.username
    LEFT JOIN subjects s ON a.subject_id = s.id
    ORDER BY a.attempt_date DESC
";
$result = mysqli_query($conn, $sql);

$admin = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Scores - Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #05000AFF, #2575fc);
  color: #fff;
  min-height: 100vh;
  display: flex;
  flex-direction: row;
}

/* Sidebar */
.sidebar {
  width: 240px;
  background: rgba(255,255,255,0.1);
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
  text-align: center; background: rgba(255,255,255,0.15);
  transition: 0.3s;
}
.menu a:hover { background: rgba(255,255,255,0.35); }

/* Main Container */
.container {
  flex: 1;
  margin: 30px;
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(12px);
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  overflow-x: auto; /* ✅ Fix table cut-off */
}

/* Headings */
h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 28px;
  color: #fff;
}
.back-btn {
  display: inline-block;
  padding: 12px 25px;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  color: #fff;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  font-size: 16px;
  text-decoration: none;
  transition: background 0.3s ease, transform 0.2s;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.back-btn:hover {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  transform: translateY(-2px);
}
.btn-container {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color: #fff;
  min-width: 700px; /* ✅ Prevents squishing */
}
th, td {
  padding: 12px 15px;
  border: 1px solid rgba(255,255,255,0.2);
  text-align: center;
}
th {
  background: rgba(0,123,255,0.7);
  font-weight: 600;
}
tbody tr {
  background: rgba(255,255,255,0.05);
  transition: 0.3s;
}
tbody tr:nth-child(even) { background: rgba(255,255,255,0.03); }
tbody tr:hover { background: rgba(255,255,255,0.1); }

/* Responsive */
@media(max-width:900px){
  body { flex-direction: column; }
  .sidebar { width: 100%; flex-direction: row; justify-content: space-between; padding: 10px 20px; border-radius: 0; }
  .sidebar h2 { display: none; }
  .container { margin: 15px; }
}
@media(max-width:500px){
  table, th, td { font-size: 12px; }
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

<!-- Main Container -->
<div class="container">
  <h2>📊 All Quiz Attempts</h2>
  <div class="btn-container">
    <a href="admin_panel.php" class="back-btn">← Back to Dashboard</a>
  </div>

  <?php if (mysqli_num_rows($result) > 0): ?>
  <table>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Subject</th>
        <th>Score</th>
        <th>Total</th>
        <th>Percentage</th>
        <th>Date</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <?php $percentage = ($row['total'] > 0) ? round(($row['score'] / $row['total']) * 100, 2) : 0; ?>
          <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['subject']) ?></td>
              <td><?= $row['score'] ?></td>
              <td><?= $row['total'] ?></td>
              <td><?= $percentage ?>%</td>
              <td><?= $row['attempt_date'] ?></td>
          </tr>
      <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p style="text-align:center; margin-top:20px;">No quiz attempts found.</p>
  <?php endif; ?>
</div>
</body>
</html>
