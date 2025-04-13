<?php 
include 'db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$user_type = $user['type']; // 'worker' or 'shopkeeper'

$receiver_id = $_GET['receiver_id'] ?? null;
$receiver_type = $_GET['receiver_type'] ?? null;

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $receiver_id = $_POST['receiver_id'];
    $receiver_type = $_POST['receiver_type'];

    $stmt = $conn->prepare("INSERT INTO jobmessages (sender_type, sender_id, receiver_type, receiver_id, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $user_type, $user_id, $receiver_type, $receiver_id, $message);
    $stmt->execute();
    $stmt->close();
}

// Get messages
$query = "SELECT * FROM jobmessages 
          WHERE 
          (sender_type = ? AND sender_id = ? AND receiver_type = ? AND receiver_id = ?) OR 
          (sender_type = ? AND sender_id = ? AND receiver_type = ? AND receiver_id = ?) 
          ORDER BY created_at";

$stmt = $conn->prepare($query);
$stmt->bind_param("sisssiss", $user_type, $user_id, $receiver_type, $receiver_id,
                                $receiver_type, $receiver_id, $user_type, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        /* Add your chat styles here */
    </style>
</head>
<body>

<!-- Display chat messages -->
<div class="chat-container">
    <h2>Chat with <?= htmlspecialchars($receiver_type) ?></h2>

    <div class="chat-messages">
        <?php while ($row = $messages->fetch_assoc()) { ?>
            <div class="message">
                <p><strong><?= htmlspecialchars($row['sender_type']) ?>:</strong> <?= htmlspecialchars($row['message']) ?></p>
                <small><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></small>
            </div>
        <?php } ?>
    </div>

    <form method="POST">
        <input type="text" name="message" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
