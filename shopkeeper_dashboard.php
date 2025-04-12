<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'shopkeeper') {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Shopkeeper Dashboard</title>
</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($user['name']) ?> (<?= $user['shop_name'] ?>)</h1>
  <p>Email: <?= htmlspecialchars($user['email']) ?></p>
  <p>Phone: <?= htmlspecialchars($user['phone']) ?></p>

  <h3>Available Workers</h3>
  <ul>
    <?php
 
    $result = $conn->query("SELECT * FROM workers");

    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['name']} - {$row['skillset']} - {$row['email']} - {$row['phone']}</li>";
    }

    $conn->close();
    ?>
  </ul>

  <a href="logout.php">Logout</a>
</body>
</html>
