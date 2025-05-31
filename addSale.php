<?php
session_start();

// Ensure the user is logged in and is an admin
if ($_SESSION['isloggedin'] != 1 || $_SESSION['role_id'] != 1) {
    header("Location: login1.php");
    exit();
}

require_once 'connection.php';

if (
    isset($_POST['product_id']) && !empty($_POST['product_id']) &&
    isset($_POST['start_sale_date']) && !empty($_POST['start_sale_date']) &&
    isset($_POST['end_sale_date']) && !empty($_POST['end_sale_date']) &&
    isset($_POST['discount']) && !empty($_POST['discount'])
) {
    // Extract form data
    $product_id = $_POST['product_id'];
    $start_sale_date = $_POST['start_sale_date'];
    $end_sale_date = $_POST['end_sale_date'];
    $discount = $_POST['discount'];

    // Validate discount percentage
    if ($discount < 0 || $discount > 100) {
        die("Discount must be between 0 and 100%");
    }

    // Validate sale dates
    if (strtotime($start_sale_date) > strtotime($end_sale_date)) {
        die("End date must be later than the start date");
    }

    // Insert the sale details into the sale table
    $sql = "INSERT INTO sale (product_id, Start_sale_date, End_sale_date, `discount%`) 
        VALUES ($product_id, UNIX_TIMESTAMP('$start_sale_date'), UNIX_TIMESTAMP('$end_sale_date'), $discount)";

    $result = $conn->query($sql);

    if ($result) {
        echo "Sale record added successfully!";
    } else {
        die("Error inserting sale record: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet" href="footer.css">
<link rel="stylesheet" href="admin.css">
<link rel="stylesheet" href="add_sale.css">
<title>Add Sale</title>

<header>
<a href="admin.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>  
    <img src="images/image.png" alt="Logo" class="logo">
    
    <a href="admin.php" class="logout">
        <i class="fas fa-sign-out-alt"></i>
    </a>   
</header>

<body>
<div class="title">
    <h1>Add Sale</h1>
</div>
<form action="" method="post">
    <table>
        <tr>
            <td>Product:</td>
            <td>
                <select name="product_id">
                    <option value="">Select Product</option>
                    <?php
                        $product_query = "SELECT product_id, product_name FROM product";
                        $result = $conn->query($product_query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['product_id']}'>{$row['product_name']}</option>";
                            }
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Start Sale Date:</td>
            <td><input type="date" name="start_sale_date"></td>
        </tr>
        <tr>
            <td>End Sale Date:</td>
            <td><input type="date" name="end_sale_date"></td>
        </tr>
        <tr>
            <td>Discount (%):</td>
            <td><input type="number" name="discount" min="0" max="100"></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" name="submit">Add Sale</button></td>
        </tr>
    </table>
</form>
</body>
</html>
<style>
header .logo {
    height: 150px;
}

header nav {
    display: flex;
    gap: 20px;
}

header nav a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
    padding: 5px 10px;
}

header nav a:hover {
    color: #ff007f;
}

header .logout {
    text-decoration: none;
    font-size: 16px;
    color: #ff007f;
    font-weight: bold;
}

/* Page title */
.title {
    text-align: center;
    margin: 30px 0;
}

.title h1 {
    font-size: 28px;
    color: #333;
}

/* Form container */
form {
    width: 50%;
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Table styling */
form table {
    width: 100%;
    border-collapse: collapse;
}

form table tr td {
    padding: 10px 5px;
    font-size: 16px;
    color: #555;
}

form table tr td input,
form table tr td select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: #333;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button styling */
form table tr td button {
    background-color: #ff007f;
    color: #fff;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

form table tr td button:hover {
    background-color: #e6006f;
}

/* Responsive design */
@media (max-width: 768px) {
    form {
        width: 90%;
    }

    header nav {
        gap: 10px;
    }

    header .logo {
        height: 40px;
    }
}

</style>