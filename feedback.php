<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    if (!empty($feedback)) {
        $insert = "INSERT INTO feedback (username, message) VALUES ('$user', '$feedback')";
        if (mysqli_query($conn, $insert)) {
            $message = "✅ Thank you! Your feedback has been submitted.";
        } else {
            $message = "❌ Something went wrong. Please try again.";
        }
    } else {
        $message = "❌ Please write something before submitting.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Feedback / Help - QuizzyMind</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #05000AFF, #2575fc);
    color: #fff;
    margin: 0;
    padding: 40px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.feedback-box {
    max-width: 600px;
    width: 100%;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(15px);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    text-align: center;
}

h2 {
    font-size: 26px;
    margin-bottom: 25px;
    color: #fff;
}

textarea {
    width: 100%;
    height: 150px;
    padding: 15px;
    margin-bottom: 20px;
    font-size: 20px;
    border-radius: 15px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
    resize: vertical;
    transition: 0.3s ease;
}
textarea::placeholder {
    color: #93C2C4FF; 
    opacity: 1;     
}
textarea:focus {
    background: rgba(255,255,255,0.3);
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 30px;
    background: linear-gradient(90deg, #00c6ff, #0072ff);
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover {
    background: linear-gradient(90deg, #0072ff, #00c6ff);
    transform: translateY(-2px);
}

.message {
    margin-bottom: 20px;
    font-weight: bold;
    color: #00ffe0;
}
</style>
</head>
<body>

<div class="feedback-box">
    <h2>💬 Feedback / Help</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <textarea name="feedback" placeholder="Write your feedback, issue, or question here..." required></textarea>
        <button type="submit">📩 Submit Feedback</button>
    </form>

    <!-- Back Button inside the box -->
    <a href="user_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<style>
.back-btn {
    display: block;
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    text-align: center;
    border-radius: 30px;
    background: linear-gradient(90deg, #00c6ff, #0072ff);
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s ease;
}

.back-btn:hover {
    background: linear-gradient(90deg, #0072ff, #00c6ff);
    transform: translateY(-2px);
}
</style>

</body>
</html>
