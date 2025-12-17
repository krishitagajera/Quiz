<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

// DB connection
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// If subjects table is empty, insert default subjects
$check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM subjects");
if ($check) {
    $row = mysqli_fetch_assoc($check);
    if ($row['total'] == 0) {
        $insert = mysqli_query($conn, "INSERT INTO subjects (name, description) VALUES
            ('Maths', 'Covers Algebra, Arithmetic, Geometry and more.'),
            ('Science', 'Physics, Chemistry, Biology and General Science.'),
            ('History', 'Ancient, Medieval and Modern history topics.'),
            ('Geography', 'Earth, maps, continents, climates and more.'),
            ('English', 'Grammar, comprehension, and vocabulary.')");
        if (!$insert) {
            die("Error inserting default subjects: " . mysqli_error($conn));
        }
    }
} else {
    die("Error checking subjects: " . mysqli_error($conn));
}

// Fetch subjects
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
if (!$subjects) {
    die("Error fetching subjects: " . mysqli_error($conn));
}

// Subject-to-image mapping
$subjectImages = [
    'Maths'     => 'math.png',
    'Science'   => 'science.png',
    'History'   => 'history.png',
    'Geography' => 'geography.png',
    'English'   => 'english.png'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Quiz Subject - QuizzyMind</title>
  <style>
    body {
      margin: 0;
      padding: 40px;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(120deg, #05000AFF, #2575fc);
      color: #fff;
      min-height: 100vh;
    }

    h2 {
      text-align: center;
      color: #fff;
      font-size: 2em;
      margin-bottom: 40px;
    }

    .subjects-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      padding: 0 20px;
    }

    .subject-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 25px 20px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .subject-card:hover {
      transform: translateY(-5px) scale(1.03);
      box-shadow: 0 12px 30px rgba(0,0,0,0.35);
      border-color: #00c6ff;
    }

    .subject-card img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 15px;
      margin-bottom: 15px;
      border: 2px solid #00c6ff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.25);
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    .subject-card h3 {
      margin-bottom: 10px;
      color: #00ffe0;
      font-size: 1.3em;
    }

    .subject-card p {
      color: #f0f0f0;
      font-size: 14px;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .start-btn {
      display: inline-block;
      background: linear-gradient(90deg, #00c6ff, #0072ff);
      color: #fff;
      padding: 12px 28px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: bold;
      font-size: 0.95em;
      transition: all 0.3s ease;
      border: none;
    }

    .start-btn:hover {
      background: linear-gradient(90deg, #0072ff, #00c6ff);
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

  <h2>🧠 Choose a Quiz Subject</h2>
  <div style="text-align:center; margin-bottom:30px;">
    <a href="user_dashboard.php" class="start-btn">⬅ Back to Dashboard</a>
  </div>

  <div class="subjects-container">
    <?php while($subject = mysqli_fetch_assoc($subjects)): 
        $imageFile = $subjectImages[$subject['name']] ?? 'default.png';
    ?>
      <div class="subject-card">
        <img src="images/subjects/<?php echo htmlspecialchars($imageFile); ?>" 
             alt="<?php echo htmlspecialchars($subject['name']); ?>">
        <h3><?php echo htmlspecialchars($subject['name']); ?></h3>
        <p><?php echo htmlspecialchars($subject['description']); ?></p>
        <a href="take_quiz.php?subject=<?php echo strtolower($subject['name']); ?>" class="start-btn">Start Quiz</a>
      </div>
    <?php endwhile; ?>
  </div>

</body>
</html>
