<?php
session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QuizzyMind</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f4f4;
    }

    header {
        background-color: #3e2723;
        color: #fff;
        padding: 15px 0;
        text-align: center;
    }

    header .logo {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        margin: 0 15px;
        font-size: 16px;
        transition: 0.3s;
    }

    nav a:hover {
        color: #ff9800;
    }

    @media (max-width: 768px) {
        nav a {
            display: block;
            margin: 8px 0;
        }
    }
</style>
</head>
<body>
<header>
    <div class="logo">QuizzyMind</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="quiz_subjects.php">Quizzes</a>
        <a href="leaderboard.php">Leaderboard</a>
        <?php if($user): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="auth.php">Login / Signup</a>
        <?php endif; ?>
    </nav>
</header>
<main>
