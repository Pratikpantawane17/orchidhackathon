<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'shopkeeper') {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $wages = mysqli_real_escape_string($conn, $_POST['wages']);

    // Insert job posting into the database
    $stmt = $conn->prepare("INSERT INTO jobs (shopkeeper_id, job_title, job_description, location, wages, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $user['id'], $job_title, $job_description, $location, $wages);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopkeeper Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f7f6;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1200px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #333;
    }
    .tab {
      display: inline-block;
      padding: 10px 20px;
      margin-right: 10px;
      cursor: pointer;
      background: #ddd;
      border-radius: 5px;
      font-weight: bold;
    }
    .tab.active {
      background: #4CAF50;
      color: white;
    }
    .tab-content {
      display: none;
      margin-top: 20px;
    }
    .tab-content.active {
      display: block;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      padding: 10px 15px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #45a049;
    }
    ul {
      padding-left: 20px;
      list-style-type: none;
    }
    /* .logout-btn {
      display: inline-block;
      margin-top: 20px;
      background: #f44336;
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 5px;
    }
    .logout-btn:hover {
      background: #d32f2f;
    } */
    .job-card-container {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .job-card {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .job-card h4 {
      margin-bottom: 10px;
      color: #333;
      font-size: 1.5em;
    }
    .job-card p {
      color: #555;
      margin-bottom: 10px;
    }
    .recommended-workers h5 {
      color: #4CAF50;
      margin-top: 20px;
      font-size: 1.2em;
    }
    .worker-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .worker-card {
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 10px;
      width: calc(33% - 20px);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .worker-card h6 {
      font-size: 1.2em;
      margin-bottom: 10px;
      color: #333;
    }
    .worker-card p {
      margin-bottom: 8px;
      color: #555;
    }

/* Base button style */
.logout-btn {
  display: inline-block;
  margin-top: 20px;
  background: #f44336;
  color: white;
  padding: 10px 15px;
  text-decoration: none;
  border-radius: 5px;
  z-index: 1000;
}

/* Hover effect */
.logout-btn:hover {
  background: #d32f2f;
}

/* Positioning for top-right corner */
.top-right {
  position: absolute;
  top: 20px;
  right: 20px;
}

  </style>
</head>
<body>

<div class="container">

  <h1>Welcome, <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['shop_name']) ?>)</h1>
  <p>Email: <?= htmlspecialchars($user['email']) ?> | Phone: <?= htmlspecialchars($user['phone']) ?></p>

  <div class="tab active" onclick="showTab('post-job')">Post a Job</div>
  <div class="tab" onclick="showTab('my-jobs')">My Jobs</div>
  <div class="tab" onclick="showTab('workers')">Available Workers</div>

  <div id="post-job" class="tab-content active">
    <h3>Post a New Job</h3>
    <form method="POST">
      <input type="text" name="job_title" placeholder="Job Title" required>
      <textarea name="job_description" placeholder="Job Description" required></textarea>
      <input type="text" name="location" placeholder="Location" required>
      <input type="text" name="wages" placeholder="Offered Wages" required>
      <button type="submit">Post Job</button>
    </form>
  </div>

  <div id="my-jobs" class="tab-content">
    <h3>Your Posted Jobs</h3>
    <div class="job-card-container">
      <?php
      // Query to fetch jobs posted by the shopkeeper
      $result = $conn->query("SELECT * FROM jobs WHERE shopkeeper_id = {$user['id']} ORDER BY created_at DESC");
      while ($row = $result->fetch_assoc()) {
          echo "
          <div class='job-card'>
            <h4>" . htmlspecialchars($row['job_title']) . "</h4>
            <p><strong>Description:</strong> " . htmlspecialchars($row['job_description']) . "</p>
            <p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>
            <p><strong>Wages:</strong> ₹" . htmlspecialchars($row['wages']) . "</p>
            <div class='recommended-workers'>
              <h5>Recommended Workers for '" . htmlspecialchars($row['job_title']) . "'</h5>
              <div class='worker-list'>
      ";

          // Query for recommended workers based on the job title, location, and wage
          $stmt = $conn->prepare("SELECT * FROM workers WHERE 
              (skillset LIKE CONCAT('%', ?, '%') OR other_skills LIKE CONCAT('%', ?, '%'))
              AND preferred_location LIKE CONCAT('%', ?, '%')
              AND expected_fees <= ?");
          $stmt->bind_param("sssi", $row['job_title'], $row['job_title'], $row['location'], $row['wages']);
          $stmt->execute();
          $workers = $stmt->get_result();

          if ($workers->num_rows === 0) {
              echo "<p>No matching workers found.</p>";
          } else {
              // Only display the worker recommendations once for each job
              while ($worker = $workers->fetch_assoc()) {
                  echo "
                  <div class='worker-card'>
                    <h6>" . htmlspecialchars($worker['name']) . "</h6>
                    <p><strong>Skills:</strong> " . htmlspecialchars($worker['skillset']) . "</p>
                    <p><strong>Fees:</strong> ₹" . htmlspecialchars($worker['expected_fees']) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($worker['email']) . "</p>
                    <p><strong>Phone:</strong> " . htmlspecialchars($worker['phone']) . "</p>
                  </div>
                  ";
              }
          }

          // Close the worker list
          echo "</div></div></div>";
          $stmt->close();
      }
      ?>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>

  <div id="workers" class="tab-content">
  <h3 class="section-title">Available Workers</h3>
  <div class="cards-container" style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php
    $result = $conn->query("SELECT * FROM workers");
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="card" style="
          width: 250px;
          height: 250px;
          border: 1px solid #ccc;
          border-radius: 10px;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          padding: 15px;
          box-sizing: border-box;
          overflow: hidden;
          display: flex;
          flex-direction: column;
          justify-content: space-between;
        ">
          <div>
            <h4 style="margin: 0 0 10px 0;">' . htmlspecialchars($row['name']) . '</h4>
            <p><strong>Skillset:</strong> ' . htmlspecialchars($row['skillset']) . '</p>
            <p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>
            <p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>
          </div>
          <div style="font-size: 0.85em; margin-top: 10px;">
            <p><strong>Location:</strong> ' . htmlspecialchars($row['preferred_location'] ?? 'Not Set') . '</p>
            <p><strong>Fees:</strong> ' . htmlspecialchars($row['expected_fees'] ?? 'Not Set') . '</p>
          </div>
        </div>';
    }
    ?>
  </div>
</div>

</div>


<script>
function showTab(tabId) {
  document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

  document.querySelector(`[onclick="showTab('${tabId}')"]`).classList.add('active');
  document.getElementById(tabId).classList.add('active');
}
</script>

</body>
</html>
