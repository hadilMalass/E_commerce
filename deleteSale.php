<?php
require_once 'connection.php';

if (isset($_GET['sale_id']) && !empty($_GET['sale_id'])){
    $id=$_GET['sale_id'];

    $query="DELETE FROM sale WHERE sale_id=$id";
    $conn->query($query);
    header("location:showSale.php");
}
?>