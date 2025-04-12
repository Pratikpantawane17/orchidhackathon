<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'];
    $fees = $_POST['fees'];
    $skills = $_POST['other_skills'];

    $stmt = $conn->prepare("UPDATE workers SET preferred_location = ?, expected_fees = ?, other_skills = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $location, $fees, $skills, $user['id']);
    $stmt->execute();
    $stmt->close();

    // Refresh session
    $result = $conn->query("SELECT * FROM workers WHERE id = {$user['id']}");
    $_SESSION['user'] = $result->fetch_assoc();
    $user = $_SESSION['user'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Worker Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f7fa;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .container {
      max-width: 1200px;
      width: 100%;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #333;
      font-size: 2rem;
      margin-bottom: 10px;
    }

    h3 {
      font-size: 1.25rem;
      margin-bottom: 10px;
      color: #4CAF50;
    }

    .info p {
      margin: 10px 0;
      font-size: 1rem;
      color: #666;
    }

    .tabs {
      display: flex;
      border-bottom: 2px solid #ddd;
      margin-bottom: 20px;
    }

    .tab {
      padding: 10px 20px;
      cursor: pointer;
      font-size: 1rem;
      text-align: center;
      flex: 1;
      background-color: #f4f7fa;
      transition: background-color 0.3s ease;
    }

    .tab:hover {
      background-color: #e0e0e0;
    }

    .active-tab {
      background-color: #4CAF50;
      color: white;
      font-weight: bold;
    }

    .tab-content {
      display: none;
      padding-top: 20px;
    }

    .tab-content.active {
      display: block;
    }

    .timeline {
      border-left: 3px solid #4CAF50;
      margin-left: 20px;
      padding-left: 20px;
      margin-bottom: 30px;
    }

    .timeline-event {
      margin-bottom: 20px;
      position: relative;
    }

    .timeline-event::before {
      content: '';
      width: 12px;
      height: 12px;
      background-color: #4CAF50;
      border-radius: 50%;
      position: absolute;
      left: -22px;
      top: 4px;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 5px;
      border: 1px solid #ddd;
      font-size: 1rem;
    }

    button {
      width: 100%;
      padding: 15px;
      background-color: #4CAF50;
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #45a049;
    }

    .shopkeepers-list {
      list-style: none;
      padding-left: 0;
    }

    .shopkeepers-list li {
      background-color: #fafafa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 15px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      font-size: 1rem;
      color: #333;
    }

    .shopkeepers-list li span {
      font-weight: bold;
    }

    .logout-btn {
      display: inline-block;
      padding: 10px 15px;
      background-color: #f44336;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 20px;
    }

    .logout-btn:hover {
      background-color: #e53935;
    }

    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }

      .info p {
        font-size: 0.9rem;
      }

      button {
        font-size: 0.9rem;
      }
    }

  </style>
</head>
<body>

  <div class="container">
    <h1>Welcome, <?= htmlspecialchars($user['name']) ?> (<?= $user['skillset'] ?>)</h1>
    <div class="info">
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <div class="tab active-tab" onclick="showTab('profile')">Update Profile & Preferences</div>
      <div class="tab" onclick="showTab('shopkeepers')">Available Shopkeepers</div>
    </div>

    <!-- Tab Contents -->
    <div id="profile" class="tab-content active">
      <h3 class="section-title">Update Profile & Preferences</h3>
      <div class="section-content">
        <div class="card">
          <h4>Profile Timeline</h4>
          <div class="timeline">
            <div class="timeline-event">
              <strong>Registered On:</strong> <?= date('d M Y, h:i A', strtotime($user['created_at'] ?? '')) ?>
            </div>
            <div class="timeline-event">
              <strong>Last Updated Preferences:</strong> <?= date('d M Y, h:i A', strtotime($user['updated_at'] ?? $user['created_at'])) ?>
            </div>
          </div>
        </div>

        <div class="card">
          <h4>Update Your Preferences</h4>
          <form method="POST">
            <input type="text" name="location" value="<?= htmlspecialchars($user['preferred_location'] ?? '') ?>" placeholder="Preferred Location">
            <input type="text" name="fees" value="<?= htmlspecialchars($user['expected_fees'] ?? '') ?>" placeholder="Expected Wages / Fees">
            <input type="text" name="other_skills" value="<?= htmlspecialchars($user['other_skills'] ?? '') ?>" placeholder="Other Skills">
            <button type="submit">Update Preferences</button>
          </form>
        </div>
      </div>
    </div>

    <div id="shopkeepers" class="tab-content">
      <h3 class="section-title">Available Shopkeepers</h3>
      <div class="section-content">
        <div class="card">
          <ul class="shopkeepers-list">
            <?php
            $query = "SELECT * FROM shopkeepers WHERE email != '{$user['email']}'"; // exclude same email if needed
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<li><span>{$row['name']}</span> - {$row['shop_name']} - {$row['email']} - {$row['phone']}</li>";
            }
            $conn->close();
            ?>
          </ul>
        </div>
      </div>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <script>
    function showTab(tabName) {
      // Hide all tabs
      const contents = document.querySelectorAll('.tab-content');
      contents.forEach(content => {
        content.classList.remove('active');
      });

      // Remove active class from all tabs
      const tabs = document.querySelectorAll('.tab');
      tabs.forEach(tab => {
        tab.classList.remove('active-tab');
      });

      // Show the selected tab and add active class
      document.getElementById(tabName).classList.add('active');
      document.querySelector(`.tab[onclick="showTab('${tabName}')"]`).classList.add('active-tab');
    }
  </script>

</body>
</html>
