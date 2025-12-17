<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - QuizzyMind</title>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #05000A, #2575fc);
    color: #fff;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Centered box */
.main-content {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    padding: 40px 30px;
    border-radius: 20px;
    max-width: 700px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.main-content h2 {
    color: #00c6ff;
    margin-bottom: 20px;
    font-size: 28px;
}

.main-content p {
    margin-bottom: 15px;
    font-size: 16px;
    line-height: 1.6;
}

/* Back button */
.back-btn {
    display: inline-block;
    margin-bottom: 30px;
    padding: 10px 20px;
    background: rgba(0, 198, 255, 0.2);
    color: #fff;
    border: 1px solid #00c6ff;
    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.back-btn:hover {
    background: #00c6ff;
    color: #05000A;
}
</style>
</head>
<body>

<div class="main-content">
    <a href="user_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2>About QuizzyMind</h2>
    <p>Welcome to <strong>QuizzyMind</strong>, your ultimate online quiz platform. Our mission is to make learning engaging, competitive, and rewarding for everyone.</p>
    <p>At QuizzyMind, you can test your knowledge across multiple subjects, track your progress, earn badges, and climb up the leaderboard. Whether you're preparing for exams or just love learning new things, QuizzyMind is designed to help you improve and stay motivated.</p>
    <p>Our platform is easy to use, fully responsive, and constantly updated with new quizzes and features to enhance your learning experience. Join our community and start your journey toward becoming a quiz master!</p>
</div>

</body>
</html>
