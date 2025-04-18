<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'];
    $fees = $_POST['fees'];
    $skills = $_POST['other_skills'];

    $stmt = $conn->prepare("UPDATE workers SET preferred_location = ?, expected_fees = ?, other_skills = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $location, $fees, $skills, $user['id']);
    $stmt->execute();
    $stmt->close();

    // Refresh session data
    $result = $conn->query("SELECT * FROM workers WHERE id = {$user['id']}");
    $_SESSION['user'] = $result->fetch_assoc();
    $user = $_SESSION['user'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Worker Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="./css/header.css" />
  <style>
    .tab-content { display: none; }
    .active { font-weight: bold; }
    .active-tab { background: #ccc; }
    .active.tab-content { display: block; }
  </style>
  <style>
    /* Base styles */
:root {
    --color-slate-900: #0f172a;
    --color-slate-800: #1e293b;
    --color-slate-700: #334155;
    --color-slate-600: #475569;
    --color-slate-400: #94a3b8;
    --color-slate-300: #cbd5e1;
    --color-white: #ffffff;
    --container-width: 1200px;
    --transition-speed: 0.3s;
  }
  
  
  
  /* Footer styles */
  .footer {
    color: #000;
    color: black;
    
    padding: 4rem 0 1.5rem;
  }
  
  .container {
    color: black;
    width: 100%;
    max-width: var(--container-width);
    margin: 0 auto;
    padding: 0 1rem;
  }
  
  .footer-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 3rem;
  }
  
  @media (min-width: 768px) {
    .footer-grid {
      grid-template-columns: 1fr 1fr;
    }
  }
  
  @media (min-width: 1024px) {
    .footer-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }
  
  .footer-section {
    margin-bottom: 1rem;
  }
  
  .footer-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--color-white);
  }
  
  .footer-subtitle {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--color-white);
  }
  
  .footer-description {
    color: var(--color-slate-300);
    margin-bottom: 1.5rem;
    max-width: 20rem;
  }
  
  /* Contact items */
  .contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.75rem;
    color: var(--color-slate-300);
  }
  
  .icon {
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  
  /* Footer links */
  .footer-links {
    list-style: none;
  }
  
  .footer-links li {
    margin-bottom: 0.75rem;
  }
  
  .footer-link {
    display: flex;
    align-items: center;
    color: var(--color-slate-300);
    text-decoration: none;
    transition: color var(--transition-speed);
  }
  
  .footer-link:hover {
    color: var(--color-white);
  }
  
  .arrow-icon {
    margin-right: 0.5rem;
    transition: transform var(--transition-speed);
  }
  
  .footer-link:hover .arrow-icon {
    transform: translateX(4px);
  }
  
  /* Newsletter form */
  .newsletter-form {
    margin-bottom: 1.5rem;
  }
  
  .form-group {
    display: flex;
    margin-bottom: 0.5rem;
  }
  
  .newsletter-input {
    flex-grow: 1;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.25rem 0 0 0.25rem;
    outline: none;
  }
  
  .newsletter-input:focus {
    box-shadow: 0 0 0 2px var(--color-slate-500);
  }
  
  .newsletter-button {
    background-color: var(--color-slate-600);
    color: var(--color-white);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0 0.25rem 0.25rem 0;
    cursor: pointer;
    transition: background-color var(--transition-speed);
  }
  
  .newsletter-button:hover {
    /* background-color: var(--color-slate-700); */
    background-color: rgb(68, 160, 68);
  }
  
  .privacy-notice {
    font-size: 0.75rem;
    color: var(--color-slate-400);
  }
  
  /* Social section */
  .social-section {
    margin-top: 1.5rem;
  }
  
  .social-title {
    font-size: 1.125rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
  }
  
  .social-icons {
    display: flex;
    gap: 1rem;
  }
  
  .social-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    background-color: var(--color-slate-800);
    border-radius: 50%;
    color: var(--color-white);
    transition: background-color var(--transition-speed);
  }
  
  .social-icon:hover {
    background-color: var(--color-slate-700);
  }
  
  /* Footer bottom */
  .footer-divider {
    height: 1px;
    background-color: var(--color-slate-800);
    margin: 1.5rem 0;
  }
  
  .footer-bottom {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--color-slate-400);
    font-size: 0.875rem;
  }
  
  @media (min-width: 768px) {
    .footer-bottom {
      flex-direction: row;
      justify-content: space-between;
    }
  }
  
  .copyright {
    margin-bottom: 1rem;
  }
  
  @media (min-width: 768px) {
    .copyright {
      margin-bottom: 0;
      margin-top: 0px;
    }
  }
  
  .legal-links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem 2rem;
  }
  
  .legal-link {
    color: var(--color-slate-400);
    text-decoration: none;
    transition: color var(--transition-speed);
  }
  
  .legal-link:hover {
    color: var(--color-white);
  }
  








    * {
      box-sizing: border-box;
    }

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

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      background-color: white;
      padding: 15px 20px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      flex-wrap: wrap;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo1_img {
      height: 40px;
    }

    .header_logo {
      font-weight: bold;
      font-size: 1.5rem;
      color: #000;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    nav a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
    }

    .profile_photo {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    .hamburger, .close-icon {
      display: none;
    }

    .container {
      max-width: 1200px;
      width: 100%;
      padding: 30px 40px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin-top: 50px;
      color: black;
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
      flex-wrap: wrap;
    }

    .tab {
      padding: 10px 20px;
      cursor: pointer;
      font-size: 1rem;
      text-align: center;
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

    header {
      background: radial-gradient(
      circle at 80% 0%,
      #daf1f9 0%,
      #f1e8f6 50%,
      #f0f0f0 70%,
      #dfeaed9e 100%
    );
    }

    @media (max-width: 769px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .logo {
        flex-direction: row;
        width: 100%;
      }


      nav {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        gap: 10px;
        margin-top: 10px;
      }

      .container {
        padding: 15px;
        margin: 10px;
        color: black;
      }

      .info p {
        font-size: 0.9rem;
      }

      button {
        font-size: 0.9rem;
      }

      .logo {
        flex-direction: row;
        justify-content: space-between;
        width: 100%;
      }

      .footer-grid {
        collator_asort
      }

     
      
    }
  </style>
</head>
<body>

<header>
  <div class="logo">
    <img src="./assets/logo1.png" class="logo1_img" />
    <span class="header_logo">CommUnity</span>
  </div>
  <nav>
    <a href="#">Home</a>
    <a href="#">How It Works</a>
    <a href="#">About</a>
    <a href="#">Contact Us</a>
    <img src="./assets/profile_photo.png" class="profile_photo" />
  </nav>
</header>

<div class="container">
  <h1>Welcome, <?= htmlspecialchars($user['name']) ?> (<?= $user['skillset'] ?>)</h1>
  <div class="info">
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
  </div>

  <div class="tabs">
    <div class="tab active-tab" onclick="showTab('profile', this)">Update Profile & Preferences</div>
    <div class="tab" onclick="showTab('jobs', this)">Available Shopkeepers</div>
  </div>

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
          <input type="text" name="location" id="locationInput" 
                 value="<?= htmlspecialchars($user['preferred_location'] ?? '') ?>" 
                 placeholder="Preferred Location" onblur="appendCoordinates()">
          <input type="text" name="fees" value="<?= htmlspecialchars($user['expected_fees'] ?? '') ?>" placeholder="Expected Wages / Fees">
          <input type="text" name="other_skills" value="<?= htmlspecialchars($user['other_skills'] ?? '') ?>" placeholder="Other Skills">
          <button type="submit">Update Preferences</button>
        </form>
      </div>
    </div>
  </div>

  <div id="jobs" class="tab-content">
    <h3 class="section-title">Available Jobs</h3>
    <div class="section-content">
      <div class="card">
        <ul class="jobs-list" style="list-style: none; padding: 0;">
          <?php
          $query = "SELECT jobs.*, shopkeepers.name AS shopkeeper_name, shopkeepers.shop_name, shopkeepers.email, shopkeepers.phone 
                    FROM jobs 
                    JOIN shopkeepers ON jobs.shopkeeper_id = shopkeepers.id 
                    WHERE shopkeepers.email != '{$user['email']}' 
                    ORDER BY jobs.created_at DESC";
          $result = $conn->query($query);

          while ($row = $result->fetch_assoc()) {
              echo "<li style='margin-bottom: 15px; padding: 10px; border-bottom: 1px solid #ccc;'>
                      <strong>Job Title:</strong> " . htmlspecialchars($row['job_title']) . "<br>
                      <strong>Description:</strong> " . htmlspecialchars($row['job_description']) . "<br>
                      <strong>Location:</strong> " . htmlspecialchars($row['location']) . "<br>
                      <strong>Wages:</strong> " . htmlspecialchars($row['wages']) . "<br>
                      <strong>Posted By:</strong> " . htmlspecialchars($row['shopkeeper_name']) . " (" . htmlspecialchars($row['shop_name']) . ")<br>
                      <strong>Email:</strong> " . htmlspecialchars($row['email']) . "<br>
                      <strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "<br>
                      <strong>Posted At:</strong> " . htmlspecialchars($row['created_at']) . "
                    </li>";
          }
          $conn->close();
          ?>
        </ul>
      </div>
    </div>
  </div>

  <a href="logout.php" class="logout-btn">Logout</a>
</div>

<footer class="footer" style="color: black">
  <!-- Your footer content remains the same -->
</footer>

<script>
function showTab(tabId, element) {
  document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active-tab'));

  document.getElementById(tabId).classList.add('active');
  element.classList.add('active-tab');
}

function appendCoordinates() {
  // Add logic here if needed to append coordinates
  console.log("Coordinates appending logic (if needed)");
}
</script>

</body>
</html>
