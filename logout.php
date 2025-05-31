<?php
session_start();
session_destroy(); // Destroy the session
header("Location: login1.php"); // Redirect to login page
exit();
?>
