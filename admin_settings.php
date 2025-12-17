<?php
session_start();

// Only allow admin access
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch admin data
$adminId = 1; // assuming only 1 admin
$adminQuery = mysqli_query($conn, "SELECT * FROM admin WHERE id='$adminId'");
$admin = mysqli_fetch_assoc($adminQuery);

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['name']);
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($currentPassword !== $admin['password']) {
        $message = "❌ Current password is incorrect!";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "❌ New passwords do not match!";
    } else {
        $updateQuery = "UPDATE admin SET name='$newName', password='$newPassword' WHERE id='$adminId'";
        if (mysqli_query($conn, $updateQuery)) {
            $message = "✅ Settings updated successfully!";
            $adminQuery = mysqli_query($conn, "SELECT * FROM admin WHERE id='$adminId'");
            $admin = mysqli_fetch_assoc($adminQuery);
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Settings</title>
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

/* Container */
.container {
  flex: 1;
  margin: 30px;
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(12px);
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

/* Headings */
h2 {
  text-align: center;
  margin-bottom: 20px;
  font-size: 26px;
  color: #fff;
}

/* Labels & Inputs */
label {
  display: block;
  margin-top: 15px;
  font-weight: bold;
}
input[type="text"], input[type="password"] {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border-radius: 8px;
  border: none;
  outline: none;
  background: rgba(255,255,255,0.2);
  color: #fff;
}
input::placeholder { color: rgba(255,255,255,0.6); }

/* Button */
button {
  width: 100%;
  padding: 12px;
  margin-top: 20px;
  border-radius: 8px;
  border: none;
  background: #007bff;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}
button:hover { background: #0056b3; }

/* Message */
.message {
  margin-top: 15px;
  text-align: center;
  font-weight: bold;
}
.message.error { color: #ff4d4d; }
.message.success { color: #00e676; }

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
  <h2><?= htmlspecialchars($admin['name']) ?></h2>
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
<div class="container">
  <h2>⚙️ Admin Settings</h2>
  <form method="post">
      <label>Username / Email</label>
      <input type="text" name="name" value="<?= htmlspecialchars($admin['name']) ?>" required>

      <label>Current Password</label>
      <input type="password" name="current_password" required>

      <label>New Password</label>
      <input type="password" name="new_password" required>

      <label>Confirm New Password</label>
      <input type="password" name="confirm_password" required>

      <button type="submit">💾 Save Changes</button>
  </form>

  <?php if ($message): ?>
      <p class="message <?= strpos($message,'❌')!==false ? 'error':'success' ?>"><?= $message ?></p>
  <?php endif; ?>
</div>

</body>
</html>
