<?php
include 'db.php';
session_start();

$role     = $_POST['role'];
$email    = $_POST['email'];
$password = $_POST['password'];

$table = $role === "worker" ? "workers" : "shopkeepers";

$sql = "SELECT * FROM $table WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $role;

        if ($role === "worker") {
            header("Location: worker_dashboard.php");
        } else {
            header("Location: shopkeeper_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
