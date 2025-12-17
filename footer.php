</main>
<footer>
    <div class="footer-container">
        <div class="footer-left">
            <h3>QuizzyMind</h3>
            <p>Your favorite online quiz platform</p>
        </div>
        <div class="footer-center">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="quiz_subjects.php">Quizzes</a></li>
                <li><a href="leaderboard.php">Leaderboard</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <h4>Follow Us</h4>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; <?php echo date("Y"); ?> QuizzyMind. All rights reserved.
    </div>
</footer>

<style>
    footer {
        background-color: #3e2723;
        color: #fff;
        padding: 40px 20px 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        width: 100%;
    }

    .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1200px;
        margin: auto;
    }

    .footer-left, .footer-center, .footer-right {
        flex: 1 1 250px;
        margin: 10px;
        text-align: center;
    }

    .footer-left h3, .footer-center h4, .footer-right h4 {
        margin-bottom: 10px;
    }

    .footer-center ul {
        list-style: none;
        padding: 0;
    }

    .footer-center ul li {
        margin-bottom: 8px;
    }

    .footer-center ul li a {
        color: #fff;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-center ul li a:hover {
        color: #ff9800;
    }

    .footer-right a {
        color: #fff;
        margin: 0 8px;
        font-size: 18px;
        text-decoration: none;
        transition: 0.3s;
    }

    .footer-right a:hover {
        color: #ff9800;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        border-top: 1px solid #555;
        padding-top: 10px;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
