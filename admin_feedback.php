<?php
session_start();



$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch feedback data
$sql = "SELECT id, username, message, submitted_at FROM feedback ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("❌ Query Failed: " . mysqli_error($conn));
}

$admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>📋 User Feedback - QuizzyMind</title>
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
}

/* Headings */
h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 28px;
  color: #fff;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color: #fcf8f8ff;
}
th, td {
  padding: 12px 15px;
  border: 1px solid rgba(255,255,255,0.2);
  text-align: left;
  border-radius: 8px;
}
th {
  background: rgba(0,123,255,0.7);
  color: #fcf8f8ff;
  font-weight: 600;
}
tbody tr {
  background: rgba(255,255,255,0.05);
  transition: 0.3s;
}
tbody tr:nth-child(even) { background: rgba(255,255,255,0.03); }
tbody tr:hover { background: rgba(255,255,255,0.1); }

.no-feedback {
  text-align: center;
  color: #ddd;
  margin-top: 30px;
  font-size: 16px;
}

/* Back button */
.back-btn {
  display: inline-block;
  margin-top: 25px;
  padding: 10px 20px;
  border-radius: 25px;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s ease;
}
.back-btn:hover {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
}

/* Responsive */
@media(max-width:900px){
  body { flex-direction: column; }
  .sidebar { width: 100%; flex-direction: row; justify-content: space-between; padding: 10px 20px; border-radius: 0; }
  .sidebar h2 { display: none; }
  .container { margin: 15px; }
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
    <a href="admin_feedback.php">💬 View Feedback</a>
    <a href="admin_settings.php">⚙️ Settings</a>
    <a href="index.php?logout=true">🚪 Logout</a>
  </div>
</div>

<!-- Main Content -->
<div class="container">
  <h2>📋 User Feedback</h2>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Feedback</th>
          <th>Submitted At</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= htmlspecialchars($row['submitted_at']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="no-feedback">No feedback submitted yet.</p>
  <?php endif; ?>

  <a href="admin_panel.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
