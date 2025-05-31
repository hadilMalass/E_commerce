<?php
require_once 'connection.php';

if (isset($_GET['donation_id']) && !empty($_GET['donation_id'])){
    $id=$_GET['donation_id'];

    $query="DELETE FROM donation WHERE donation_id=$id";
    $conn->query($query);
    header("location:showDonationTable.php");
}
?>

