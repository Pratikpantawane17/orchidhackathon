<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
       body {
        background: radial-gradient(
      circle at 80% 0%,
      #daf1f9 0%,
      #f1e8f6 50%,
      #f0f0f0 70%,
      #dfeaed9e 100%
    );
       }
    </style>
</head>
<body class="">

<!-- Ad Popup -->
<div id="ad-popup" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-70 hidden items-center justify-center z-50">
    <div class="relative bg-white p-6 rounded-lg w-[90%] max-w-md text-center">
        <h2 class="text-2xl font-bold mb-2">Special Advertisement</h2>
        <p class="mb-4">Check out our latest deals!</p>
        <img src="./uploads/6772ddb31266e_retail-shop.jpg" alt="Advertisement" class="w-full h-56 object-cover mb-4 rounded">
        <a href="ad.html" class="text-blue-500 underline">Go to Ad Page</a>
        <button id="close-ad" class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center text-lg">×</button>
    </div>
</div>

<!-- Header -->


<!-- Header -->
<header class="py-4 shadow-md">
  <div class="container mx-auto px-4 flex flex-col lg:flex-row justify-between items-center gap-4">
    
    <!-- Left: Logo Section -->
    <div class="flex items-center space-x-3">
      <img src="./assets/logo1.png" alt="CommUnity Logo" class="rounded-full w-11 h-11">
      <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-800">CommUnity</h1>
    </div>

    <!-- Middle: Primary Nav Links -->
    <nav class="flex flex-wrap justify-center gap-3">
    <a href="dashboard.php" class="px-4 py-2 hover:bg-gray-200 transition">Dashboard</a>
    <a href="post-request.php" class="px-4 py-2 hover:bg-gray-200 transition">Post Request</a>
      <a href="offer-help.php" class="px-4 py-2 hover:bg-gray-200 transition">Offer Help</a>
      <a href="profile.php" class="px-4 py-2 hover:bg-gray-200 transition">Profile</a>
      <a href="ad.html" class="px-4 py-2 hover:bg-gray-200 transition">Advertisement</a>
      <a href="logout.php" class="px-4 py-2 hover:bg-gray-200 transition">Logout</a>
    </nav>

  </div>
</header>


<!-- Main Content -->
<main class="container mx-auto px-4 py-10">
    <h2 class="text-3xl font-bold mb-8 text-center">Help Requests</h2>

    <div class="flex flex-wrap justify-center gap-6">
        <?php
        $sql = "SELECT requests.*, users.name AS user_name 
                FROM requests 
                JOIN users ON requests.user_id = users.id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='bg-white border-4 border-black rounded-xl shadow-lg p-6 w-full sm:w-80 relative flex flex-col justify-between min-h-[400px]'>
                        <h3 class='text-xl font-semibold text-center text-cyan-700 mb-4'>{$row['title']}</h3>
                        <div class='flex-1'>
                            <p class='mb-2'><strong>Description:</strong><br>" . nl2br(htmlspecialchars($row['description'])) . "</p>
                            <p class='mb-1'><strong>Category:</strong> {$row['category']}</p>
                            <p class='mb-1'><strong>Request ID:</strong> {$row['id']}</p>
                            <p class='mb-1'><strong>Posted by:</strong> {$row['user_name']}</p>
                        </div>
                        <div class='text-center mt-4'>
                            <a href='messages.php?request_id={$row['id']}' class='inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition'>View Message</a>
                        </div>
                      </div>";
            }
        } else {
            echo "<p class='text-center text-lg'>No requests available.</p>";
        }
        ?>
    </div>
</main>

<script>
    window.onload = () => {
        document.getElementById('ad-popup').style.display = 'flex';
    };

    document.getElementById('close-ad').onclick = () => {
        document.getElementById('ad-popup').style.display = 'none';
    };
</script>

</body>
</html>
