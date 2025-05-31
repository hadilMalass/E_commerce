<?php
require_once 'connection.php';

// Check if order_id is provided in the query string
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $id = $_GET['order_id'];

    // Query to delete the order from the order table
    $query = "DELETE FROM `order` WHERE order_id = $id";
    
    // Execute the query
    if ($conn->query($query) === TRUE) {
        header("location:order.php"); // Redirect to the orders page after deletion
    } else {
        echo "Error deleting order: " . $conn->error;
    }
}
?>
