<?php
require_once 'connection.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch the existing product information
    $query = "SELECT * FROM product WHERE product_id = $product_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Product not found.</p>";
        exit();
    }

    // Fetch the current sizes associated with the product
    $size_query = "SELECT size_id FROM product_size WHERE product_id = $product_id";
    $sizes_result = $conn->query($size_query);
    $selected_sizes = [];
    while ($size_row = $sizes_result->fetch_assoc()) {
        $selected_sizes[] = $size_row['size_id'];
    }

    // Fetch the current colors associated with the product
    $color_query = "SELECT color_id FROM product_colors WHERE product_id = $product_id";
    $colors_result = $conn->query($color_query);
    $selected_colors = [];
    while ($color_row = $colors_result->fetch_assoc()) {
        $selected_colors[] = $color_row['color_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the product information
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $category_id = $_POST['category_id'];

    // Update query for the product
    $update_query = "UPDATE product 
                     SET product_name = '$product_name', description = '$description', price = '$price', quantity = '$quantity', categories_id = '$category_id'
                     WHERE product_id = $product_id";

    if ($conn->query($update_query) === TRUE) {
        // First, delete existing size and color associations
        $delete_size_query = "DELETE FROM product_size WHERE product_id = $product_id";
        $conn->query($delete_size_query);

        $delete_color_query = "DELETE FROM product_colors WHERE product_id = $product_id";
        $conn->query($delete_color_query);

        // Then, insert the new size and color associations
        if (isset($_POST['sizes'])) {
            foreach ($_POST['sizes'] as $size_id) {
                $size_insert_query = "INSERT INTO product_size (product_id, size_id) VALUES ($product_id, $size_id)";
                $conn->query($size_insert_query);
            }
        }

        if (isset($_POST['color'])) {
            foreach ($_POST['color'] as $color_id) {
                $color_insert_query = "INSERT INTO product_colors (product_id, color_id) VALUES ($product_id, $color_id)";
                $conn->query($color_insert_query);
            }
        }

        echo "<p>Product updated successfully.</p>";
        echo "<a href='showClothes.php'>Go back to clothes page</a>";
    } else {
        echo "<p>Error updating product: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="add_clothes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit Product</title>
</head>
<body>
    <header>
        <img src="images/image.png" alt="Logo" class="logo">
        <a href="showClothes.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
    </header>

    <div class="title">
        <h1>Edit Product</h1>
    </div>

    <form action="edit.php?product_id=<?php echo $product_id; ?>" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" value="<?php echo $row['product_name']; ?>" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br>

        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo $row['price']; ?>" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" required><br>

        <label for="category_id">Category ID:</label>
        <input type="text" name="category_id" value="<?php echo $row['categories_id']; ?>" required><br>

        <label for="sizes">Sizes:</label>
        <?php
        $size_query = "SELECT * FROM size";
        $size_result = $conn->query($size_query);
        while ($size_row = $size_result->fetch_assoc()) {
            $checked = in_array($size_row['size_id'], $selected_sizes) ? 'checked' : '';
            echo "<input type='checkbox' name='sizes[]' value='{$size_row['size_id']}' $checked> {$size_row['age_size']}<br>";
        }
        ?>

        <label for="color">Colors:</label>
        <?php
        $color_query = "SELECT * FROM color";
        $color_result = $conn->query($color_query);
        while ($color_row = $color_result->fetch_assoc()) {
            $checked = in_array($color_row['color_id'], $selected_colors) ? 'checked' : '';
            echo "<input type='checkbox' name='color[]' value='{$color_row['color_id']}' $checked> {$color_row['name_color']}<br>";
        }
        ?>
<label for="image">Product Image:</label>
        <input type="file" name="image" accept="image/*"><br>
        <?php if (!empty($row['image'])): ?>
            <p>Current Image:</p>
            <img src="productImage/<?php echo $row['image']; ?>" alt="Product Image" style="width:100px;"><br>
        <?php endif; ?>
        <button type="submit">Update Product</button>
    </form>
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