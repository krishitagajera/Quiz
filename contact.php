<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}

$user = $_SESSION['user'];

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "login");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - QuizzyMind</title>
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

/* Centered Box */
.contact-box {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    padding: 40px 30px;
    border-radius: 20px;
    max-width: 700px;
    width: 100%;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.contact-box h2 {
    color: #00c6ff;
    margin-bottom: 20px;
    font-size: 28px;
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

/* Form */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 15px;
}
input, textarea {
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-family: inherit;
    outline: none;
}
textarea {
    resize: none;
    height: 120px;
}
button {
    padding: 12px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    background: #00c6ff;
    color: #05000A;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #0094cc;
    color: #fff;
}
</style>
</head>
<body>

<div class="contact-box">
    <a href="user_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2>Contact Us</h2>
    <p>Have questions or feedback? We'd love to hear from you! Fill out the form below:</p>

    <form method="post" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" required></textarea>
        <button type="submit" name="send"><i class="fas fa-paper-plane"></i> Send Message</button>
    </form>

    <?php
    if (isset($_POST['send'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        $sql = "INSERT INTO contact_messages (user, name, email, message) 
                VALUES ('$user', '$name', '$email', '$message')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<p style='margin-top:15px; color:#00ff99;'>✅ Thank you, $name! Your message has been sent successfully.</p>";
        } else {
            echo "<p style='margin-top:15px; color:#ff4444;'>❌ Error: " . mysqli_error($conn) . "</p>";
        }
    }
    ?>
</div>

</body>
</html>
