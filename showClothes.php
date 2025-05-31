<html>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="add_clothes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header>
        <img src="images/image.png" alt="Logo" class="logo">
       
        <a href="admin.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
    </header>
    <style>
       
    .logo{
        width: 160px;
    }
</style>
    
</html>

<?php
require_once 'connection.php';

$query = "
    SELECT 
        p.product_id, 
        p.product_name, 
        p.description, 
        p.categories_id, 
        p.price, 
        p.quantity, 
        p.image, 
        GROUP_CONCAT(DISTINCT s.age_size SEPARATOR ', ') AS sizes, 
        GROUP_CONCAT(DISTINCT c.name_color SEPARATOR ', ') AS colors
    FROM product p
    LEFT JOIN product_size ps ON p.product_id = ps.product_id
    LEFT JOIN size s ON ps.size_id = s.size_id
    LEFT JOIN product_colors pc ON p.product_id = pc.product_id
    LEFT JOIN color c ON pc.color_id = c.color_id
    GROUP BY p.product_id
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border=1>";
    echo "<tr>";
    echo "<th>Product ID</th>";
    echo "<th>Product Name</th>";
    echo "<th>Description</th>";
    echo "<th>Category ID</th>";
    echo "<th>Sizes</th>";
    echo "<th>Colors</th>";
    echo "<th>Price ($)</th>";
    echo "<th>Quantity</th>";
    echo "<th>Image</th>";
    echo "<th>Delete</th>";
    echo "<th>Edit</th>";

    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        $id = $row['product_id'];
        $name = $row['product_name'];
        $description = $row['description'];
        $category_id = $row['categories_id'];
        $sizes = $row['sizes'];
        $colors = $row['colors'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $image = $row['image'];

        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$name</td>";
        echo "<td>$description</td>";
        echo "<td>$category_id</td>";
        echo "<td>$sizes</td>";
        echo "<td>$colors</td>";
        echo "<td>$price</td>";
        echo "<td>$quantity</td>";
        echo "<td><a href='images/$image'><img src='productImage/$image' alt='$name'></a></td>";
        echo "<td><a href='delete.php?product_id=$id'><img src='images/trash.png' alt='Delete'></a></td>";
        echo "<td><a href='edit.php?product_id=$id'><img src='images/edit.png' alt='Delete'></a></td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No products found.</p>";
}
?>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<!-- <a href="addproduct.php" class="logout-icon"><i class="fas fa-plus"></i></a> -->
</body>
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
