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

// Fetch users
$query = "SELECT id, name, email, username, created_at FROM users";
$result = mysqli_query($conn, $query);

$admin = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users - Admin</title>
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
.btn-container {
  text-align: center;
  margin-bottom: 20px;
  display: flex;
  justify-content: center;
}

.back-btn:hover {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  transform: translateY(-2px);
}


/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color: #fff;
}
th, td {
  padding: 12px 15px;
  border: 1px solid rgba(255,255,255,0.2);
  text-align: center;
  border-radius: 8px;
}
th {
  background: rgba(0,123,255,0.7);
  color: #fff;
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
  <h2>👥 Registered Users</h2>

  <div class="btn-container">
  <a href="admin_panel.php" class="back-btn">← Back to Dashboard</a>
  </div>

  <?php if (mysqli_num_rows($result) > 0): ?>
  <table>
      <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Username</th>
          <th>Registered At</th>
      </tr>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= $row['created_at'] ?></td>
          </tr>
      <?php endwhile; ?>
  </table>
  <?php else: ?>
    <p style="text-align:center; margin-top:20px;">No users found.</p>
  <?php endif; ?>
</div>
</body>
</html>
