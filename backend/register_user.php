<?php 
require 'db_conn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = $email . '.jpg';
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); 
        }
        $imagePath = $uploadDir . $imageName;
        $relativePath = '../../uploads/' . $imageName;
        if (move_uploaded_file($imageTmp, $imagePath)) {
            echo "Image uploaded successfully.";
        } else {
            echo "Failed to upload the image.";
        }
    }
    $stmt = $conn->prepare("INSERT INTO user (name, email, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $imagePath); 

    if ($stmt->execute()) {
        echo 'Registered the user successfully';
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>