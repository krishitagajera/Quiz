<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) die("Connection failed: " . mysqli_connect_error());

$message = "";
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'login';

// LOGIN
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // First check in admin table
    $adminCheck = mysqli_query($conn, "SELECT * FROM admin WHERE name='$username' AND password='$password'");
    if (mysqli_num_rows($adminCheck) === 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_panel.php");
        exit();
    }

    // Then check in users table
    $userCheck = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($userCheck) === 1) {
        $_SESSION['user'] = $username;
        header("Location: user_dashboard.php");
        exit();
    }

    $message = "Invalid username or password!";
}

// REGISTER
if (isset($_POST['register'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $exists = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($exists) > 0) {
        $message = "User already exists!";
        $mode = 'signup';
    } else {
        $insert = "INSERT INTO users (name, email, username, password) 
                   VALUES ('$name', '$email', '$username', '$password')";
        if (mysqli_query($conn, $insert)) {
            $_SESSION['user'] = $username;
            header("Location: user_dashboard.php");
            exit();
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Signup</title>
    <style>
        body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(120deg, #05000AFF, #2575fc);
  color: #fff;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.box {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(15px);
  padding: 30px 40px;
  border-radius: 20px;
  width: 360px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  text-align: center;
  color: #fff;
}

h2 {
  font-size: 28px;
  margin-bottom: 25px;
}

input {
  width: 90%;
  padding: 12px 15px;
  margin: 10px 0;
  border-radius: 12px;
  border: none;
  outline: none;
  font-size: 16px;
  background: rgba(255, 255, 255, 0.15);
  color: #fff;
  box-shadow: inset 0 0 8px rgba(255, 255, 255, 0.3);
  transition: background 0.3s ease;
}

input::placeholder {
  color: #ddd;
}

input:focus {
  background: rgba(255, 255, 255, 0.3);
}

button {
  padding: 12px 25px;
  margin-top: 15px;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  border: none;
  border-radius: 50px;
  color: #fff;
  font-weight: 600;
  font-size: 16px;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.6);
  transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  transform: translateY(-2px);
}

a {
  display: inline-block;
  margin-top: 20px;
  color: #d0d0d0;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

a:hover {
  color: #fff;
}

.msg {
  color: #ff6b6b;
  font-weight: 600;
  margin-bottom: 15px;
}

    </style>
</head>
<body>
<div class="box">
    <h2><?= $mode === 'signup' ? 'Sign Up (User Only)' : 'Login (Admin or User)' ?></h2>
    <?php if ($message) echo "<p class='msg'>$message</p>"; ?>

    <?php if ($mode === 'signup'): ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button name="register">Create Account</button>
        </form>
        <a href="?mode=login">Already have an account? Login</a>
    <?php else: ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username or Admin Name" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button name="login">Login</button>
        </form>
        <a href="?mode=signup">Don't have a user account? Sign Up</a>
    <?php endif; ?>
</div>
</body>
</html>
