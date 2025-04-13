<!-- <?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // File upload
    $aadharImageName = $_FILES['aadhar_image']['name'];
    $aadharTmpName   = $_FILES['aadhar_image']['tmp_name'];

    // Create uploads directory if not exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadPath = $uploadDir . basename($aadharImageName);

    if (move_uploaded_file($aadharTmpName, $uploadPath)) {
        // Save info to DB including image path
        $sql = "INSERT INTO users (name, email, password, aadhar_image) VALUES ('$name', '$email', '$password', '$uploadPath')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.html");
            exit(); // Always call exit after redirecting
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload Aadhar image.";
    }
}
?> -->
