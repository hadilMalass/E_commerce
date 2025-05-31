<?php
require_once 'connection.php';

// Get sale_id from the URL query string
if (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];

    // Query to fetch the sale data for the specific sale_id
    $query = "SELECT * FROM sale WHERE sale_id = $sale_id";
    $result = $conn->query($query);

    // Check if sale exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_id = $row['product_id'];
        $start_sale_date = $row['Start_sale_date'];
        $end_sale_date = $row['End_sale_date'];
        $discount = $row['discount%'];

        // Convert Unix timestamps to human-readable format
        $start_sale_date = date("Y-m-d\TH:i:s", $start_sale_date);
        $end_sale_date = date("Y-m-d\TH:i:s", $end_sale_date);
    } else {
        echo "Sale not found.";
        exit;
    }
} else {
    echo "Sale ID is missing.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values from the form
    $new_start_sale_date = strtotime($_POST['start_sale_date']);
    $new_end_sale_date = strtotime($_POST['end_sale_date']);
    $new_discount = $_POST['discount'];  // New discount value

    // Query to update the sale data with corrected column name for discount
    $update_query = "UPDATE sale SET Start_sale_date = $new_start_sale_date, End_sale_date = $new_end_sale_date, `discount%` = $new_discount WHERE sale_id = $sale_id";
    
    if ($conn->query($update_query)) {
        echo "Sale updated successfully!";
    } else {
        echo "Error updating sale: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<header>
        <img src="images/image.png" alt="Logo" class="logo">
        <a href="showClothes.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
    </header>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sale</title>
</head>
<body>

    <h1>Edit Sale</h1>
    <form action="editSale.php?sale_id=<?php echo $sale_id; ?>" method="post">
        <label for="product_id">Product ID:</label>
        <input type="text" id="product_id" name="product_id" value="<?php echo $product_id; ?>" disabled><br><br>

        <label for="start_sale_date">Start Sale Date:</label>
        <input type="datetime-local" id="start_sale_date" name="start_sale_date" value="<?php echo $start_sale_date; ?>" required><br><br>

        <label for="end_sale_date">End Sale Date:</label>
        <input type="datetime-local" id="end_sale_date" name="end_sale_date" value="<?php echo $end_sale_date; ?>" required><br><br>

        <label for="discount">Discount (%):</label>
        <input type="number" id="discount" name="discount" value="<?php echo $discount; ?>" min="0" max="100" required><br><br>

        <input type="submit" value="Update Sale">
    </form>

    <br>
    <a href="showSale.php">Back to Sales</a>
</body>
</html>
<style>
    
    body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            margin: 30px 0;
        }

        .title h1 {
            font-size: 32px;
            color: #333;
        }

        /* Form container */
        form {
            width: 60%;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        /* Form labels and inputs */
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        /* Textarea specific styling */
        textarea {
            height: 120px;
            resize: vertical;
        }

        /* Button Styling */
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 90%;
            }
        }

        /* General Styling for Links */
        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
       

    </style>