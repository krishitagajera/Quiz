<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// DB Connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Allowed subject tables
$allowed_tables = [
    "english"   => "questions_english",
    "maths"      => "questions_maths",
    "science"   => "questions_science",
    "history"   => "questions_history",
    "geography" => "questions_geography"
];

$message = "";

// Add Question
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) {
    $subject = $_POST['subject'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct = $_POST['correct'];

    if (array_key_exists($subject, $allowed_tables)) {
        $table = $allowed_tables[$subject];
        $insert = "INSERT INTO $table (question, option1, option2, option3, option4, correct_answer)
                   VALUES ('$question','$option1','$option2','$option3','$option4','$correct')";
        if (mysqli_query($conn, $insert)) {
            $message = "✅ Question added successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}

// Delete Question
if (isset($_GET['delete']) && isset($_GET['subject'])) {
    $id = intval($_GET['delete']);
    $subject = $_GET['subject'];

    if (array_key_exists($subject, $allowed_tables)) {
        $table = $allowed_tables[$subject];
        mysqli_query($conn, "DELETE FROM $table WHERE id=$id");
        $message = "Question deleted successfully!";
    }
}

$admin = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Add Questions</title>
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

/* Messages */
.msg {
  text-align: center;
  font-weight: bold;
  margin-bottom: 15px;
  color: #BCE2D3FF;
}

/* Form */
form {
  margin-bottom: 20px;
  text-align: center;
}
select, input, textarea, button {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid rgba(255,255,255,0.3);
  background: rgba(255,255,255,0.05);
  color: #000000FF;
  margin: 5px 0;
  font-size: 14px;
}
textarea { resize: none; }

/* Buttons */
button {
  background: rgba(0,123,255,0.7);
  color:#000000FF;
  border: none;
  cursor: pointer;
  transition: 0.3s;
}
button:hover { background: rgba(0,123,255,1); }

/* Add Form Toggle */
.add-btn {
  background: rgba(23,162,184,0.7);
  color:#000000FF;
  margin-bottom: 15px;
  cursor: pointer;
  border-radius: 10px;
  border: none;
  padding: 12px;
  transition: 0.3s;
}
.add-btn:hover { background: rgba(23,162,184,1); }

.add-form {
  background: rgba(255,255,255,0.05);
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.2);
  padding: 20px;
  margin-bottom: 20px;
  display: none;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  color: #000000FF;
}
th, td {
  padding: 12px 15px;
  border: 1px solid rgba(255,255,255,0.2);
  text-align: center;
  border-radius: 8px;
}
th {
  background: rgba(0,123,255,0.7);
  color: #000000FF;
  font-weight: 600;
}
tbody tr {
  background: rgba(255,255,255,0.05);
  transition: 0.3s;
}
tbody tr:nth-child(even) { background: rgba(255,255,255,0.03); }
tbody tr:hover { background: rgba(255,255,255,0.1); }

/* Edit/Delete buttons */
.btn {
  padding: 6px 12px;
  border-radius: 8px;
  text-decoration: none;
  margin: 2px;
  display: inline-block;
  font-size: 13px;
  transition: 0.3s;
}
.edit { background: rgba(40,167,69,0.8); color: #000000FF; }
.edit:hover { background: rgba(40,167,69,1); }
.delete { background: rgba(220,53,69,0.8); color: #000000FF; }
.delete:hover { background: rgba(220,53,69,1); }

/* Responsive */
@media(max-width:900px){
  body { flex-direction: column; }
  .sidebar { width: 100%; flex-direction: row; justify-content: space-between; padding: 10px 20px; border-radius: 0; }
  .sidebar h2 { display: none; }
  .container { margin: 15px; }
}
</style>
<script>
function toggleAddForm() {
    var form = document.getElementById("addForm");
    form.style.display = (form.style.display === "none") ? "block" : "none";
}
</script>
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
<div class="container">
  <h2>Manage Questions</h2>
  <?php if ($message) echo "<p class='msg'>$message</p>"; ?>

  <!-- Add Question Button -->
  <button class="add-btn" onclick="toggleAddForm()">➕ Add New Question</button>

  <!-- Add Question Form -->
  <div id="addForm" class="add-form">
    <form method="post">
      <label>Select Subject:</label>
      <select name="subject" required>
        <?php foreach ($allowed_tables as $key => $tbl): ?>
          <option value="<?= $key ?>"><?= ucfirst($key) ?></option>
        <?php endforeach; ?>
      </select><br>

      <textarea name="question" placeholder="Enter Question" required></textarea><br>
      <input type="text" name="option1" placeholder="Option 1" required><br>
      <input type="text" name="option2" placeholder="Option 2" required><br>
      <input type="text" name="option3" placeholder="Option 3" required><br>
      <input type="text" name="option4" placeholder="Option 4" required><br>
      <input type="text" name="correct" placeholder="Correct Answer" required><br>
      <button type="submit" name="add_question">Save Question</button>
    </form>
  </div>

  <!-- Subject Selector -->
  <form method="get">
    <label><b>Select Subject:</b></label>
    <select name="subject" required>
      <option value="">--Choose--</option>
      <?php foreach ($allowed_tables as $key => $tbl): ?>
        <option value="<?= $key ?>" <?= (isset($_GET['subject']) && $_GET['subject']==$key) ? "selected" : "" ?>>
          <?= ucfirst($key) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit">View</button>
  </form>

<?php
if (isset($_GET['subject']) && array_key_exists($_GET['subject'], $allowed_tables)) {
    $subject = $_GET['subject'];
    $table = $allowed_tables[$subject];
    $result = mysqli_query($conn, "SELECT * FROM $table ORDER BY id DESC");

    echo "<h3 style='text-align:center;'>Questions for " . ucfirst($subject) . "</h3>";
    echo "<table>
            <tr>
              <th>ID</th>
              <th>Question</th>
              <th>Options</th>
              <th>Correct Answer</th>
              <th>Actions</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['question']}</td>
                <td>
                  1) {$row['option1']} <br>
                  2) {$row['option2']} <br>
                  3) {$row['option3']} <br>
                  4) {$row['option4']}
                </td>
                <td>{$row['correct_answer']}</td>
                <td>
                  <a class='btn edit' href='edit_question.php?subject=$subject&id={$row['id']}'>Edit</a>
                  <a class='btn delete' href='?subject=$subject&delete={$row['id']}' onclick=\"return confirm('Delete this question?');\">Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}
?>
</div>
</body>
</html>
