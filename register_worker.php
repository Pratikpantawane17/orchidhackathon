<?php
include 'db.php';

$name     = $_POST['name'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];
$skillset = $_POST['skillset'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO workers (name, email, phone, skillset, password)
        VALUES ('$name', '$email', '$phone', '$skillset', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: joblogin.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
