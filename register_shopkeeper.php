<?php
include 'db.php';

$name      = $_POST['name'];
$email     = $_POST['email'];
$phone     = $_POST['phone'];
$shop_name = $_POST['shop_name'];
$password  = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO shopkeepers (name, email, phone, shop_name, password)
        VALUES ('$name', '$email', '$phone', '$shop_name', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: joblogin.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
