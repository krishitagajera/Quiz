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

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    if (mysqli_num_rows($check) === 1) {
        $_SESSION['username'] = $username;
        header("Location: auth.php");
        exit();
    } else {
        $message = "Invalid login. Try again or sign up.";
    }
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
            $_SESSION['username'] = $username;
            header("Location: auth.php");
            exit();
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// LOGOUT

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Signup</title>
    <style>
        body {
            background: #ead6ff;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
        }
        button {
            padding: 10px 20px;
            background: #7a4cd4;
            color: white;
            border: none;
            cursor: pointer;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            color: #333;
            text-decoration: none;
        }
        .msg { color: red; }
    </style>
</head>
<body>
<div class="box">
    <?php if (isset($_SESSION['username'])): ?>
        <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
        <a href="?logout=true"><button>Logout</button></a>
    <?php else: ?>
        <h2><?= $mode === 'signup' ? 'Sign Up' : 'Login' ?></h2>
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
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button name="login">Login</button>
            </form>
            <a href="?mode=signup">Don't have an account? Sign Up</a>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
