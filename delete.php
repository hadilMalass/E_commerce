<?php
require_once 'connection.php';

if (isset($_GET['product_id']) && !empty($_GET['product_id'])){
    $id=$_GET['product_id'];

    $query="DELETE FROM product WHERE product_id=$id";
    $conn->query($query);
    header("location:showClothes.php");
}
?>

