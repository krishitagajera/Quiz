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

$username = $_SESSION['user'];

// Fetch current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newName = mysqli_real_escape_string($conn, $_POST['name']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $fileName = "";

    // Profile picture upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $fileName = time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile);
    }

    // Update query
    if ($fileName) {
        $stmt = $conn->prepare("UPDATE users SET username=?, name=?, email=?, profile_pic=? WHERE username=?");
        $stmt->bind_param("sssss", $newUsername, $newName, $newEmail, $fileName, $username);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, name=?, email=? WHERE username=?");
        $stmt->bind_param("ssss", $newUsername, $newName, $newEmail, $username);
    }

    if ($stmt->execute()) {
        $message = "✅ Settings updated successfully!";
        $_SESSION['user'] = $newUsername; // Update session if username changed

        // Refresh user data
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $newUsername);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
}

// Determine which picture to show
$profilePic = "uploads/default.png"; // fallback
if (!empty($user['profile_pic']) && file_exists("uploads/" . $user['profile_pic'])) {
    $profilePic = "uploads/" . $user['profile_pic'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Account Settings - QuizzyMind</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(120deg, #05000AFF, #2575fc);
    color: #fff;
    margin: 0;
    padding: 40px;
    min-height: 100vh;
}

.container {
    max-width: 500px;
    margin: auto;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(15px);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 26px;
    color: #fff;
}

label {
    display: block;
    font-weight: bold;
    margin-top: 15px;
}

input[type="text"], input[type="email"], input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    border-radius: 15px;
    border: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
    outline: none;
    transition: 0.3s ease;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="file"]:focus {
    background: rgba(255,255,255,0.3);
}

button {
    background: linear-gradient(90deg, #00c6ff, #0072ff);
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 30px;
    margin-top: 20px;
    width: 100%;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s ease;
}

button:hover {
    background: linear-gradient(90deg, #0072ff, #00c6ff);
    transform: translateY(-2px);
}

.message {
    margin-top: 15px;
    text-align: center;
    color: #00ffe0;
}

img.profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: block;
    margin: 0 auto 15px;
    object-fit: cover;
    border: 3px solid #00c6ff;
}
</style>
</head>
<body>
<div class="container">
    <h2>⚙️ Account Settings</h2>

    <img class="profile-pic" src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture">

    <form method="post" enctype="multipart/form-data">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>

        <label>Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

        <label>Email Address</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <label>Profile Picture</label>
        <input type="file" name="profile_pic" accept="image/*">

        <button type="submit">💾 Save Changes</button>
    </form>

        <!-- Back Button -->
    <div style="margin-top:15px; text-align:center;">
        <a href="user_dashboard.php" 
           style="display:inline-block; background:linear-gradient(90deg,#00c6ff,#0072ff); 
                  color:#fff; padding:12px 28px; border-radius:30px; 
                  text-decoration:none; font-weight:bold; transition:0.3s ease;">
            ⬅ Back to Dashboard
        </a>
    </div>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>
</div>
</body>
</html>
