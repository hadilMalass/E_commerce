<?php
session_start();
require_once 'connection.php';

// Get the submitted email and password
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the user exists in the database
$sql = "SELECT * FROM user WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User found, fetch details
    $user = $result->fetch_assoc();
    $_SESSION['isloggedin'] = true;
    $_SESSION['user_name'] = $user['first_name']; // Assuming 'first_name' is the user's name column
    $_SESSION['last_name'] = $user['last_name']; // Assuming 'first_name' is the user's name column
    $_SESSION['role_id'] = $user['role_id']; // Store the role ID in the session

    if ($user['role_id'] == 1) {
        // Redirect to admin page if the user is an admin
        header("Location: admin.php");
        exit();
    } else {
        // Redirect to home page for other users
        header("Location: home.php");
        exit();
    }
} else {
    // Invalid credentials
    $_SESSION['login_error'] = "Invalid email or password";
    header("Location: login1.php");
    exit();
}
?>
