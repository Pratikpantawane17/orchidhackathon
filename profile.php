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
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <header class="bg-white shadow-md p-6 flex flex-col items-center">
        <a href="dashboard.php">
            <h1 class="text-4xl font-bold text-indigo-600">CommUnity</h1>
        </a>
        <nav class="mt-4 flex space-x-4 text-lg">
            <a href="post-request.php" class="text-blue-600 hover:underline">Post Request</a>
            <a href="offer-help.php" class="text-blue-600 hover:underline">Offer Help</a>
            <a href="ad.html" class="text-blue-600 hover:underline">Job</a>
            <a href="profile.php" class="text-blue-600 hover:underline font-semibold">Profile</a>
            <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
        </nav>
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
</body>
</html>
