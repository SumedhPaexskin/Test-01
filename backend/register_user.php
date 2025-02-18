<?php 
require 'db_conn.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $imagePath = '';

    // Image upload (if an image was uploaded)
    print_r($_FILES);die;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = $email;
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }
        $imagePath = $uploadDir . $imageName; // Full filesystem path for moving the file
        $relativePath = 'uploads/' . $imageName; // Relative path to store in the database
        // Move the uploaded image to the uploads directory
        if (move_uploaded_file($imageTmp, $imagePath)) {
            echo "Image uploaded successfully.";
        } else {
            echo "Failed to upload the image.";
        }
    }
    // Prepare and bind the SQL query to insert data into the users table
    $stmt = $conn->prepare("INSERT INTO user (name, email, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $imagePath); // Bind the parameters (name, email, image_path)

    // Execute the query
    if ($stmt->execute()) {
        header("Location: ../app/login.html?msg=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>