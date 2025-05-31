<?php
require_once 'connection.php';

if (!empty($_POST["first_name"]) && !empty($_POST["last_name"])
    && !empty($_POST["password"]) && !empty($_POST["email"])) {
    
    // Sanitize user input
    $first_name = $conn->real_escape_string($_POST["first_name"]);
    $last_name = $conn->real_escape_string($_POST["last_name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Hash the password
    // $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    // Check if a user with the same email already exists
    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        header("Location: login1.php");
        exit();
    } else {
        // Insert the new user into the database
        $query2 = "INSERT INTO user (first_name, last_name, password, email, role_id)
                   VALUES (?, ?, ?, ?, 2)";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("ssss", $first_name, $last_name, $password, $email);

        if ($stmt2->execute()) {
            // Registration successful, redirect to login
            header("Location: login1.php");
            exit();
        } else {
            die("Error: " . $conn->error);
        }
    }
} else {
    echo "All fields are required.";
}
?>
