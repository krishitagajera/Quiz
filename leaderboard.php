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

$subject_id = isset($_GET['subject']) ? intval($_GET['subject']) : 0;

// Get subjects for dropdown
$subjects = mysqli_query($conn, "SELECT * FROM subjects");

// Build SQL query
$whereClause = $subject_id > 0 ? "WHERE attempts.subject_id = $subject_id" : "";

$sql = "
  SELECT attempts.*, subjects.name AS subject_name 
  FROM attempts 
  JOIN subjects ON attempts.subject_id = subjects.id 
  $whereClause
  ORDER BY score DESC, attempt_date ASC
  LIMIT 50
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard - QuizzyMind</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(120deg, #05000AFF, #2575fc);
      color: #fff;
      margin: 0;
      padding: 40px;
      min-height: 100vh;
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 26px;
      color: #fff;
    }

    .container {
      width: 90%;
      max-width: 1000px;
      margin: auto;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(15px);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    }

    .filter-form {
      text-align: center;
      margin-bottom: 20px;
    }

    select {
      padding: 10px;
      font-size: 16px;
      border-radius: 25px;
      border: none;
      outline: none;
      background: rgba(255,255,255,0.2);
      color: #0d0c0cff;
      transition: 0.3s ease;
    }

    select:hover, select:focus {
      background: rgba(255,255,255,0.3);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 15px;
      overflow: hidden;
    }

    th, td {
      padding: 14px 18px;
      text-align: center;
      font-size: 15px;
    }

    th {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    td {
      background: rgba(255, 255, 255, 0.08);
      color: #f1f1f1;
    }

    tr:hover td {
      background: rgba(255, 255, 255, 0.2);
      transition: 0.3s ease;
    }

    .rank {
      font-weight: bold;
      color: #00ffe0;
    }

    .no-data {
      text-align: center;
      padding: 20px;
      font-style: italic;
      color: #ddd;
    }

    .back-btn {
      text-align: center;
      margin-top: 20px;
    }

    .back-btn a {
      display: inline-block;
      padding: 12px 28px;
      font-size: 16px;
      color: #fff;
      text-decoration: none;
      border-radius: 30px;
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      transition: all 0.3s ease;
    }

    .back-btn a:hover {
      background: linear-gradient(90deg, #0072ff, #00c6ff);
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🏆 Leaderboard</h2>

    <div class="filter-form">
      <form method="GET">
        <label for="subject">Filter by Subject: </label>
        <select name="subject" onchange="this.form.submit()">
          <option value="0">All Subjects</option>
          <?php while ($sub = mysqli_fetch_assoc($subjects)): ?>
            <option value="<?= $sub['id'] ?>" <?= ($sub['id'] == $subject_id) ? 'selected' : '' ?>>
              <?= $sub['name'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </form>
    </div>

    <table>
      <tr>
        <th>Rank</th>
        <th>User</th>
        <th>Subject</th>
        <th>Score</th>
        <th>Total</th>
        <th>Date</th>
      </tr>

      <?php 
      $rank = 1;
      if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)): 
      ?>
        <tr>
          <td class="rank">#<?= $rank++ ?></td>
          <td><?= htmlspecialchars($row['user']) ?></td>
          <td><?= htmlspecialchars($row['subject_name']) ?></td>
          <td><?= $row['score'] ?></td>
          <td><?= $row['total'] ?></td>
          <td><?= date('d M Y, h:i A', strtotime($row['attempt_date'])) ?></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="6" class="no-data">No attempts found.</td></tr>
      <?php endif; ?>
    </table>

    <div class="back-btn">
      <a href="user_dashboard.php">⬅ Back to Dashboard</a>
    </div>
  </div>
</body>
</html>
