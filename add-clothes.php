<?php
session_start();

// Ensure the user is logged in and is an admin
if ($_SESSION['isloggedin'] != 1 || $_SESSION['role_id'] != 1) {
    header("Location: login1.php");
    exit();
}

require_once 'connection.php';

if (
    isset($_POST['name']) && !empty($_POST['name']) &&
    isset($_POST['description']) && !empty($_POST['description']) &&
    isset($_POST['price'])  &&
    isset($_POST['quantity']) && !empty($_POST['quantity']) &&
    isset($_POST['categories_id']) && !empty($_POST['categories_id']) &&
    isset($_POST['sizes']) && !empty($_POST['sizes']) &&
    isset($_POST['color']) && !empty($_POST['color'])
) {
    // Handle the image upload
    if (!empty($_FILES['image']['name'])) {
        if ($_FILES['image']['size'] > 3 * 1024 * 1024) { // 3MB max size
            die("Image should not exceed 3 MB");
        }

        $image = $_FILES['image']['name'];
        $extension = pathinfo($image, PATHINFO_EXTENSION);

        // Validate the file type
        if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg' && $extension != 'webp') {
            die('Image should be in png, jpg, jpeg, or webp format');
        }

        // Move the uploaded file to the "productImage" directory
        move_uploaded_file($_FILES['image']['tmp_name'], "productImage/$image");

        // Extract form data
        extract($_POST);
        $sizes = $_POST['sizes']; // Array of selected sizes
        $colors = $_POST['color']; // Array of selected colors

        // Escape the inputs to prevent SQL injection
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $categories_id = (int)$_POST['categories_id']; // Ensure it's an integer
        $price = (float)$_POST['price']; // Ensure it's a float
        $quantity = (int)$_POST['quantity']; // Ensure it's an integer

        // Insert the product details into the `product` table (only once)
        $sql = "INSERT INTO product (product_name, description, categories_id, price, quantity, image) 
                VALUES ('$name', '$description', $categories_id, $price, $quantity, '$image')";
        $result = $conn->query($sql);

        if ($result) {
            $product_id = $conn->insert_id; // Get the last inserted product ID

            // Insert sizes into the `product_sizes` table (for each size)
            foreach ($sizes as $size_id) {
                $size_sql = "INSERT INTO product_size (product_id, size_id) VALUES ($product_id, $size_id)";
                $size_result = $conn->query($size_sql);
                if (!$size_result) {
                    die('Error inserting into product_sizes: ' . $conn->error);
                }
            }

            // Insert colors into the `product_colors` table (for each color)
            foreach ($colors as $color_id) {
                $color_sql = "INSERT INTO product_colors (product_id, color_id) VALUES ($product_id, $color_id)";
                $color_result = $conn->query($color_sql);
                if (!$color_result) {
                    die('Error inserting into product_colors: ' . $conn->error);
                }
            }

            echo "Product added successfully!";
        } else {
            die('Error inserting product: ' . $conn->error);
        }
    } else {
        die("Please upload an image for the product");
    }
}
?>

<!-- HTML form for adding clothes -->
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="add_clothes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <title>Add clothes</title>

    <header>
        <img src="images/image.png" alt="Logo" class="logo">
        <a href="admin.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>   
    </header>

<body>
<div class="title">
    <h1>Add clothes</h1>
</div>
<form action="" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>Clothes Name:</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>Description:</td>
            <td><textarea name="description"></textarea></td>
        </tr>
        <tr>
            <td>Categorie:</td>
            <td>
                <select name="categories_id" id="">
                    <option value="">Select Category</option>
                    <?php
                        $categories_query = "SELECT categories_id, categories_name FROM categories";
                        $result = $conn->query($categories_query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['categories_id']}'>{$row['categories_name']}</option>";
                            }
                        }
                    ?> 
                </select>
            </td>
        </tr>
        <tr>
            <td>Size:</td>
            <td>
                <?php
                    $size_query = "SELECT size_id, age_size FROM size";
                    $result = $conn->query($size_query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<input type='checkbox' name='sizes[]' value='{$row['size_id']}'> {$row['age_size']}";
                        }
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td>Color:</td>
            <td>
                <?php
                    $color_query = "SELECT color_id, name_color FROM color";
                    $result = $conn->query($color_query);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<input type='checkbox' name='color[]' value='{$row['color_id']}'> {$row['name_color']}";
                        }
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td>Price:</td>
            <td><input type="number" name="price" min="0" step="0.01"></td>
        </tr>
        <tr>
            <td>Quantity:</td>
            <td><input type="number" name="quantity"></td>
        </tr>
        <tr>
            <td>Image:</td>
            <td><input type="file" name="image"></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" name="submit">Add Free Clothing Item</button></td>
        </tr>
    </table>
</form>
</body>
</html>
