<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

$user = $_SESSION['user'];
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

// Fetch current hashed password from DB
$res = mysqli_query($conn, "SELECT password FROM users WHERE username = '$user'");
$userData = mysqli_fetch_assoc($res);
$current_hashed = $userData['password'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($old, $current_hashed)) {
        $message = "<p class='error'>❌ Current password is incorrect.</p>";
    } elseif ($new !== $confirm) {
        $message = "<p class='error'>❌ New passwords do not match.</p>";
    } else {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE user SET password = '$new_hash' WHERE username = '$user'");
        if ($update) {
            $message = "<p class='success'>✅ Password changed successfully!</p>";
        } else {
            $message = "<p class='error'>❌ Error updating password.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Change Password - QuizzyMind</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f3f3f3;
      padding: 60px;
      display: flex;
      justify-content: center;
    }

    .container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 100%;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input[type="password"] {
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    button {
      background: #007bff;
      color: #fff;
      padding: 12px;
      border: none;
      font-size: 16px;
      border-radius: 25px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #0056b3;
    }

    .error {
      color: red;
      text-align: center;
    }

    .success {
      color: green;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>🔒 Change Password</h2>

    <?= $message ?>

    <form method="POST">
      <input type="password" name="old_password" placeholder="Current Password" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
      <button type="submit">Update Password</button>
    </form>
  </div>

</body>
</html>
