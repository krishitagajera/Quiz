<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Connect DB
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user = $_SESSION['user'];

// ✅ Fetch stats from DB
$q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM attempts WHERE user = '$user'");
$row = mysqli_fetch_assoc($q);
$totalQuizzes = $row['total'] ?? 0;

$q2 = mysqli_query($conn, "SELECT MAX(score) AS best FROM attempts WHERE user = '$user'");
$row2 = mysqli_fetch_assoc($q2);
$bestScore = $row2['best'] ?? 0;

$q3 = mysqli_query($conn, "SELECT AVG(score) AS avg_score FROM attempts WHERE user = '$user'");
$row3 = mysqli_fetch_assoc($q3);
$avgScore = round($row3['avg_score'] ?? 0);

// ✅ Streak calculation
$streak = 0;
$q4 = mysqli_query($conn, "SELECT DISTINCT DATE(attempt_date) AS attempt_date 
                           FROM attempts 
                           WHERE user = '$user' 
                           ORDER BY attempt_date DESC");
$dates = [];
while ($r = mysqli_fetch_assoc($q4)) {
    $dates[] = $r['attempt_date'];
}

if (!empty($dates)) {
    $today = new DateTime(); 
    $yesterday = (clone $today)->modify('-1 day');

    foreach ($dates as $i => $d) {
        $attemptDate = new DateTime($d);
        if ($i === 0) {
            if ($attemptDate->format("Y-m-d") == $today->format("Y-m-d") ||
                $attemptDate->format("Y-m-d") == $yesterday->format("Y-m-d")) {
                $streak = 1;
            } else {
                break;
            }
        } else {
            $prevDate = new DateTime($dates[$i-1]);
            $diff = $prevDate->diff($attemptDate)->days;
            if ($diff == 1) {
                $streak++;
            } else {
                break;
            }
        }
    }
}

// ✅ Level calculation
if ($totalQuizzes < 5) {
    $level = 1;
    $progressToNext = ($totalQuizzes / 5) * 100;
} elseif ($totalQuizzes < 10) {
    $level = 2;
    $progressToNext = (($totalQuizzes - 5) / 5) * 100;
} elseif ($totalQuizzes < 20) {
    $level = 3;
    $progressToNext = (($totalQuizzes - 10) / 10) * 100;
} elseif ($totalQuizzes < 30) {
    $level = 4;
    $progressToNext = (($totalQuizzes - 20) / 10) * 100;
} else {
    $level = 5;
    $progressToNext = 100;
}

// Badges with unlock conditions
$badges = [
    ["name" => "🧠 Quiz Master", "unlocked" => $totalQuizzes >= 10, "tip" => "Complete 10 quizzes"],
    ["name" => "🏆 High Scorer", "unlocked" => $bestScore >= 90, "tip" => "Score 90% or above"],
    ["name" => "🔥 Streak Keeper", "unlocked" => $streak >= 5, "tip" => "Maintain a 5-day streak"],
    ["name" => "🎯 Consistent Learner", "unlocked" => $avgScore >= 75, "tip" => "Keep an average of 75%+"],
    ["name" => "📘 Beginner", "unlocked" => true, "tip" => "Start your first quiz"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard - QuizzyMind</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* General */
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
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 0 20px 20px 0;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    transition: 0.3s ease;
}
.sidebar img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid #fff;
    margin-bottom: 15px;
}
.sidebar h2 { margin: 5px 0 20px; font-size: 18px; text-align: center; }
.menu { width: 100%; display: flex; flex-direction: column; gap: 15px; }
.menu a {
    text-decoration: none; color: #fff;
    padding: 12px; border-radius: 12px;
    text-align: center; background: rgba(255, 255, 255, 0.15);
    transition: 0.3s;
}
.menu a:hover { background: rgba(255, 255, 255, 0.35); }

/* Hamburger */
.hamburger { display: none; font-size: 26px; cursor: pointer; background: none; border: none; color: #fff; }

/* Main */
.main { flex: 1; padding: 30px; display: flex; flex-direction: column; gap: 30px; }
.greeting { text-align: center; }
.greeting h1 { font-size: 28px; margin-bottom: 5px; }

/* Stats */
.stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; }
.card {
    padding: 20px; border-radius: 16px; text-align: center;
    background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px);
    transition: 0.3s;
}
.card:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.3); }

/* Progress Bar */
.progress { width: 100%; height: 12px; background: rgba(255, 255, 255, 0.25); border-radius: 8px; margin-top: 8px; overflow: hidden; }
.progress span { display: block; height: 100%; background: linear-gradient(90deg, #00c6ff, #0072ff); width: 0; border-radius: 8px; transition: width 1s ease-in-out; }

/* Motivation */
.motivation {
    text-align: center; font-size: 18px; font-style: italic;
    background: rgba(255, 255, 255, 0.15); padding: 20px; border-radius: 15px;
    backdrop-filter: blur(12px);
}

/* Level & Badges */
.level-box {
    text-align: center; padding: 20px; border-radius: 20px;
    background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px);
}
.badges { display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; }
.badge {
    position: relative;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 10px 15px;
    font-size: 16px; font-weight: bold;
    transition: 0.3s; opacity: 1;
}
.badge.locked { opacity: 0.4; filter: grayscale(100%); }
.badge:hover { transform: scale(1.1); background: rgba(255, 255, 255, 0.35); }

/* Tooltip */
.badge::after {
    content: attr(data-tip);
    position: absolute;
    bottom: -35px; left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.75);
    color: #fff; padding: 5px 8px;
    font-size: 12px; border-radius: 6px;
    opacity: 0; pointer-events: none;
    white-space: nowrap;
    transition: opacity 0.3s ease;
}
.badge:hover::after { opacity: 1; }

/* Actions */
.actions {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}
.action {
    display: block;
    background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px);
    padding: 30px; border-radius: 20px; text-align: center;
    font-size: 18px; cursor: pointer; transition: 0.3s;
    text-decoration: none; color: #fff;
}
.action:hover { background: rgba(255, 255, 255, 0.3); transform: scale(1.05); }

footer {
      background: #05000A;
      background: linear-gradient(135deg, #05000A, #2575fc);
      color: #fff;
      padding: 40px 20px 20px;
      font-family: 'Poppins', sans-serif;
    }
    .footer-container {
      max-width: 1200px;
      margin: auto;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
    }
    footer h3, footer h4 {
      color: #00c6ff;
      margin-bottom: 15px;
    }
    footer p, footer ul li a {
      color: #e0e0e0;
      line-height: 1.6;
      text-decoration: none;
    }
    .footer-links ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .footer-links ul li {
      margin-bottom: 10px;
    }
    .footer-links ul li a:hover {
      color: #00c6ff;
    }
    .footer-social .social-icons {
      display: flex;
      gap: 15px;
    }
    .footer-social .social-icons a {
      color: #fff;
      font-size: 20px;
      width: 40px; height: 40px;
      background: rgba(255,255,255,0.1);
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%; transition: 0.3s;
    }
    .footer-social .social-icons a:hover {
      color: #00c6ff;
      background: rgba(255,255,255,0.25);
    }
    .footer-bottom {
      text-align: center;
      margin-top: 30px;
      border-top: 1px solid rgba(255,255,255,0.2);
      padding-top: 15px;
      font-size: 14px;
      color: #aaa;
    }

    /* Responsive */
    @media(max-width: 768px) {
      .footer-container {
        flex-direction: column;
        text-align: center;
      }
      .footer-about, .footer-links, .footer-contact, .footer-social {
        margin-bottom: 20px;
      }
    }

/* Responsive */
@media (max-width: 900px) {
  body { flex-direction: column; }
  .sidebar { width: 100%; border-radius: 0; flex-direction: row; justify-content: space-between; align-items: center; padding: 10px 20px; }
  .sidebar h2 { display: none; }
  .menu { display: none; flex-direction: column; width: 100%; margin-top: 10px; }
  .menu.show { display: flex; }
  .hamburger { display: block; }
  .main { padding: 15px; }
  .stats { grid-template-columns: 1fr 1fr; }
  .actions { grid-template-columns: 1fr; }
  .greeting h1 { font-size: 22px; }
}
@media (max-width: 768px) {
    .footer-container { flex-direction: column; text-align: center; }
    .footer-about, .footer-links, .footer-social { margin-bottom: 20px; }
}
@media(max-width: 500px) {
  .stats { grid-template-columns: 1fr; }
  .badge { font-size: 14px; padding: 8px 10px; }
}
</style>
</head>
<body>

<!-- Sidebar / Topbar -->
<div class="sidebar">
    <img src="profile.jpg" alt="User">
    <h2><?= $user ?></h2>
    <button class="hamburger" onclick="toggleMenu()">☰</button>
    <div class="menu" id="menu">
      <a href="take_quiz.php">🧠 Quiz</a>
      <a href="subjects.php">📚 Subjects</a>
      <a href="attempt_history.php">📜 History</a>
      <a href="leaderboard.php">🥇 Leaderboard</a>
      <a href="settings.php">⚙️ Settings</a>
      <a href="feedback.php">📝 Feedback</a>
      <a href="index.php?logout=true">🚪 Logout</a>
    </div>
</div>

<!-- Main -->
<div class="main">
<div class="greeting">
  <h1>Welcome back, <?= $user ?> 👋</h1>
  <p>✨ Keep learning, your next achievement is one quiz away!</p>
</div>

<!-- Stats -->
<div class="stats">
  <div class="card"><h3>Total Quizzes</h3><p><?= $totalQuizzes ?></p></div>
  <div class="card"><h3>Best Score</h3><p><?= $bestScore ?>%</p></div>
  <div class="card"><h3>Streak</h3><p><?= $streak ?> days</p></div>
  <div class="card">
    <h3>Avg. Score</h3>
    <p><?= $avgScore ?>%</p>
    <div class="progress"><span style="width: <?= $avgScore ?>%;"></span></div>
  </div>
</div>

<!-- Motivation -->
<div class="motivation">
<?php
$quotes = [
  "“Success is the sum of small efforts, repeated day in and day out.” – R. Collier",
  "“The mind is not a vessel to be filled, but a fire to be kindled.” – Plutarch",
  "“Don’t watch the clock; do what it does. Keep going.” – Sam Levenson",
  "“Education is the most powerful weapon which you can use to change the world.” – Nelson Mandela",
  "“Believe you can and you're halfway there.” – Theodore Roosevelt"
];
echo $quotes[array_rand($quotes)];
?>
</div>

<!-- Level -->
<div class="level-box">
  <h2>🎯 Level <?= $level ?></h2>
  <p>Progress to next level</p>
  <div class="progress"><span style="width: <?= $progressToNext ?>%;"></span></div>
</div>

<!-- Badges -->
<div class="level-box">
  <h2>🏅 Your Badges</h2>
  <div class="badges">
    <?php foreach ($badges as $badge): ?>
      <div class="badge <?= $badge['unlocked'] ? '' : 'locked' ?>" data-tip="<?= $badge['tip'] ?>">
        <?= $badge['name'] ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Actions -->
<div class="actions">
  <a href="subjects.php" class="action">📘 View Subjects</a>
  <a href="take_quiz.php" class="action">📝 Take Quiz</a>
  <a href="leaderboard.php" class="action">🏆 Leaderboard</a>
  <a href="attempt_history.php" class="action">📊 History</a>
</div>

<!-- footer.php -->
<footer>
  <div class="footer-container">
    
    <!-- About Section -->
    <div class="footer-about">
      <h3>QuizzyMind</h3>
      <p>Your favorite online quiz platform. Learn, compete, and track your progress in one place. Sharpen your skills and climb the leaderboard!</p>
    </div>

    <!-- Quick Links -->
    <div class="footer-links">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="user_dashboard.php"><i class="fa-solid fa-house"></i> Home</a></li>
        <li><a href="about.php"><i class="fa-solid fa-circle-info"></i> About Us</a></li>
        <li><a href="contact.php"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div class="footer-contact">
      <h4>Contact</h4>
      <p><i class="fa-solid fa-envelope"></i> info@quizzymind.com</p>
      <p><i class="fa-solid fa-phone"></i> +91 99887 98224</p>
      <p><i class="fa-solid fa-location-dot"></i> India</p>
    </div>

    <!-- Social Media -->
    <div class="footer-social">
      <h4>Follow Us</h4>
      <div class="social-icons">
        <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    &copy; <?= date("Y") ?> QuizzyMind. All Rights Reserved.
  </div>

</footer>


<script>
function toggleMenu() {
  document.getElementById("menu").classList.toggle("show");
}
</script>

</body>
</html>
