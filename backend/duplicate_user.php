<?php
require 'db_conn.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["exists" => true, "message" => "User already exists."]);
    } else {
        echo json_encode(["exists" => false, "message" => "User does not exist."]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["error" => "Invalid request method."]);
}
?>
