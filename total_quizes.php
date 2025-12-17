<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get logged-in user
$user = $_SESSION['user'];

// Count total quizzes attempted by this user
$query = "SELECT COUNT(*) AS total FROM quiz_attempts WHERE user = '$user'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$totalQuizzes = $row['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Total Quizzes</title>
</head>
<body>
    <h2>Hello, <?php echo htmlspecialchars($user); ?>!</h2>
    <p>You have attempted <b><?php echo $totalQuizzes; ?></b> quizzes in total.</p>
</body>
</html>
