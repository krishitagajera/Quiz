<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

// DB connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// Fetch subjects
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Select Quiz Subject</title>
<style>
body { font-family: Arial, sans-serif; background: #fef8ee; padding: 40px; }
h2 { text-align: center; color: #007bff; }
.subjects-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 40px; }
.subject-card { background: #fff; border-radius: 15px; padding: 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.subject-card h3 { color: #28a745; margin-bottom: 10px; }
.start-btn { display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 25px; margin-top: 10px; }
.start-btn:hover { background: #0056b3; }
</style>
</head>
<body>
<h2>🧠 Choose a Quiz Subject</h2>
<div class="subjects-container">
<?php while($subject = mysqli_fetch_assoc($subjects)): ?>
    <div class="subject-card">
        <h3><?= htmlspecialchars($subject['name']) ?></h3>
        <p><?= htmlspecialchars($subject['description']) ?></p>
        <a href="quiz.php?subject=<?= $subject['id'] ?>" class="start-btn">Start Quiz</a>
    </div>
<?php endwhile; ?>
</div>
</body>
</html>
