<?php
session_start();
if (!isset($_SESSION['isloggedin']) || !$_SESSION['isloggedin']) {
    header("Location: login1.php");
    exit();
}
?>
