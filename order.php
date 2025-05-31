<html> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="admin.css">
        
        <header>
       
   
        <a href="admin.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
        </header></html>
<?php
require_once 'connection.php';

$query = "SELECT * FROM `order`";
$result = $conn->query($query);
echo "<table border=1>";
echo "<tr>";
echo "<th>order ID</th>";
echo "<th>User Name</th>";
echo "<th>Phone Number</th>";
echo "<th>payment method</th>";
echo "<th>country</th>";
echo "<th>Total Product</th>";
echo "<th>Total Price</th>";
echo "<th>Done</th>";
echo "</tr>";
while ($row = $result->fetch_assoc()) {
    $id = $row['order_id'];
    $name = $row['name'];
    $phone_number=$row['phone_number'];
    $method = $row['method'];
    $country = $row['country'];
    $total_product = $row['total_product'];
    $total_price = $row['total_price'];
    
    

    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$name</td>";
    echo "<td>$phone_number</td>";
    echo "<td>$method</td>";
    echo "<td>$country</td>";
    echo "<td>$total_product</td>";
    echo "<td>$total_price  $</td>";

   echo "<td><a href='deleteorder.php?order_id=$id'><img src='images/done.png' alt='Delete'></a></td>";
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
