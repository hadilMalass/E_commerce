<html> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="admin.css">
        
        <header>
       
   
        <a href="admin.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
        </header></html>
<?php
require_once 'connection.php';

// Query to fetch sales data with date filtering
$query = "SELECT * FROM sale WHERE NOW() BETWEEN FROM_UNIXTIME(Start_sale_date) AND FROM_UNIXTIME(End_sale_date)";
$result = $conn->query($query);
echo "<table>";
echo "<tr>";
echo "<th>Sale ID</th>";
echo "<th>Product ID</th>";
echo "<th>Start Sale Date</th>";
echo "<th>End Sale Date</th>";
echo "<th>Discount (%)</th>";
echo "<th>Done</th>";
echo "<th>Edit</th>";

echo "</tr>";

while ($row = $result->fetch_assoc()) {
    $sale_id = $row['sale_id'];
    $product_id = $row['product_id'];
    $start_sale_date = $row['Start_sale_date'];
    $end_sale_date = $row['End_sale_date'];
    $discount = $row['discount%'];

    // Convert Unix timestamps to a human-readable format
    $start_sale_date = date("Y-m-d H:i:s", $start_sale_date);
    $end_sale_date = date("Y-m-d H:i:s", $end_sale_date);

    echo "<tr>";
    echo "<td>$sale_id</td>";
    echo "<td>$product_id</td>";
    echo "<td>$start_sale_date</td>";
    echo "<td>$end_sale_date</td>";
    echo "<td>$discount%</td>";
    echo "<td><a href='deleteSale.php?sale_id=$sale_id'><img src='images/done.png' alt='Delete'></a></td>";
    echo "<td><a href='editSale.php?sale_id=$sale_id'><img src='images/edit.png' alt='Delete'></a></td>";

    echo "</tr>";
}
echo "</table>";
?>

<style>
    body {
        background: url('3.jpg') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
    }
    .logout-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 1.5em;
        color: white;
        background-color: #FF6347;
        padding: 10px;
        border-radius: 50%;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .logout-icon:hover {
        background-color: #FF4500;
    }
    table {
        width: 90%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 1em;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: pink;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #ddd;
    }
    a {
        color: #4CAF50;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
        color: #45a049;
    }
    img {
        width: 50px;
        height: auto;
        border-radius: 5px;
    }
</style>
