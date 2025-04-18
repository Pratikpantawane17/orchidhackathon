<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $username = $user['name'];
    $email = $user['email'];
    $profilePhoto = $user['profile_photo'] ? $user['profile_photo'] : 'default-avatar.png';
} else {
    echo "User not found.";
    exit();
}

// Fetch posts
$sql = "SELECT * FROM requests WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
         body {
        font-family: 'Arial', sans-serif;
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
<body class="bg-gray-100 text-gray-800 font-sans">


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
      <a href="profile.php" class="px-4 py-2 hover:bg-gray-200 transition"  style="font-weight: 800">Profile</a>
      <a href="ad.html" class="px-4 py-2 hover:bg-gray-200 transition">Advertisement</a>
      <a href="logout.php" class="px-4 py-2 hover:bg-gray-200 transition">Logout</a>
    </nav>

  </div>
</header>





    <main class="max-w-4xl mx-auto mt-10">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <img src="<?php echo htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="w-32 h-32 rounded-full object-cover border-4 ">
            <h2 class="mt-4 text-2xl font-semibold"><?= htmlspecialchars($username) ?></h2>
            <p class="text-gray-500"><?= htmlspecialchars($email) ?></p>
            <a href="dashboard.php" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Back to Dashboard</a>

            <!-- Upload form -->
            <form action="upload_photo.php" method="POST" enctype="multipart/form-data" class="mt-6 w-full max-w-md text-center">
                <label for="profile_photo" class="block mb-2 text-sm font-medium text-gray-600">Update Profile Photo</label>
                <input type="file" name="profile_photo" id="profile_photo" accept="image/*" class="block w-full mb-4 border border-gray-300 rounded p-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Upload</button>
            </form>
        </div>

        <!-- User's Posts -->
        <section class="mt-10">
            <h2 class="text-2xl font-bold mb-4 text-center">Your Posts</h2>

            <?php if ($result->num_rows === 0): ?>
                <p class="text-center text-gray-500">No posts found for this user.</p>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded-lg p-5 mb-6">
                        <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($row['title']) ?></h3>
                        <p><span class="font-semibold">Description:</span> <?= htmlspecialchars($row['description']) ?></p>
                        <!-- <p><span class="font-semibold">Type:</span> <?= htmlspecialchars($row['type']) ?></p> -->
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Post Image" class="mt-4 rounded-md w-full max-w-sm">
                        <?php endif; ?>
                        <div class="mt-4 flex gap-4">
                            <!-- View Button -->
                            <form action="messages.php" method="get">
                                <?=$row['id']?>
                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">View</button>
                            </form>

                            <!-- Delete Button -->
                            <form action="delete_request.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>
    </main>
    <script
    src="https://app.wonderchat.io/scripts/wonderchat.js"
    data-name="wonderchat"
    data-address="app.wonderchat.io"
    data-id="cm9f5mmos10uzkbvo3u2j4kiy"
    data-widget-size="normal"
    data-widget-button-size="normal"
  
    defer
  ></script>
</body>
</html>
