<?php
require_once 'connection.php';

if (isset($_GET['categories_id']) && !empty($_GET['categories_id'])) {
    $id = $_GET['categories_id'];

    // Query to delete the category from the categories table
    $query = "DELETE FROM categories WHERE categories_id = $id";
    $conn->query($query);

    // Redirect back to the categories table page
    header("location:showcategory.php");
}
?>
