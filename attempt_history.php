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

// Fetch attempts with subject name
$sql = "SELECT a.id, s.name AS subject, a.score, a.total, a.attempt_date
        FROM attempts a
        LEFT JOIN subjects s ON a.subject_id = s.id
        WHERE a.user = ?
        ORDER BY a.attempt_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attempt History</title>
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
  width: 90%;
  max-width: 1000px;
  margin: auto;
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(15px);
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

h2 {
  text-align: center;
  margin-bottom: 25px;
  font-size: 26px;
  color: #fff;
}

/* Table */
table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 15px;
  overflow: hidden;
}

th, td {
  padding: 14px 18px;
  text-align: center;
  font-size: 15px;
}

th {
  background: rgba(255, 255, 255, 0.2);
  color: #fff;
  text-transform: uppercase;
  letter-spacing: 1px;
}

td {
  background: rgba(255, 255, 255, 0.08);
  color: #f1f1f1;
}

tr:hover td {
  background: rgba(255, 255, 255, 0.2);
  transition: 0.3s ease;
}

.no-data {
  text-align: center;
  padding: 20px;
  font-style: italic;
  color: #ddd;
}
.back-btn {
  text-align: center;
  margin-top: 20px;
}

.back-btn a {
  display: inline-block;
  padding: 12px 28px;
  font-size: 16px;
  color: #fff;
  text-decoration: none;
  border-radius: 30px;
  background: linear-gradient(90deg, #00c6ff, #0072ff);
  box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  transition: all 0.3s ease;
}

.back-btn a:hover {
  background: linear-gradient(90deg, #0072ff, #00c6ff);
  transform: translateY(-3px);
}

    </style>
</head>
<body>
<div class="container">
    <h2>Your Attempt History</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Score</th>
            <th>Total</th>
            <th>Percentage</th>
            <th>Date</th>
        </tr>
        <?php if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $percentage = ($row['total'] > 0) ? round(($row['score'] / $row['total']) * 100, 2) : 0;
                echo "<tr>
                        <td>{$row['subject']}</td>
                        <td>{$row['score']}</td>
                        <td>{$row['total']}</td>
                        <td>{$percentage}%</td>
                        <td>{$row['attempt_date']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No attempts found.</td></tr>";
        } ?>
    </table>
        <div class="back-btn">
        <a href="user_dashboard.php">⬅ Back to Dashboard</a>
    </div>

</div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
