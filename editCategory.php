<?php
require_once 'connection.php';

// Check if the category ID is provided
if (isset($_GET['categories_id']) && !empty($_GET['categories_id'])) {
    $categories_id = $_GET['categories_id'];

    // Fetch the existing category details
    $query = "SELECT * FROM categories WHERE categories_id = $categories_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categories_name = $row['categories_name'];
    } else {
        die("Category not found!");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['categories_name']) && !empty($_POST['categories_name'])) {
        $new_name = $conn->real_escape_string($_POST['categories_name']);

        // Update the category name
        $update_query = "UPDATE categories SET categories_name = '$new_name' WHERE categories_id = $categories_id";
        if ($conn->query($update_query)) {
            header("Location: showcategory.php");
            exit();
        } else {
            echo "Error updating category: " . $conn->error;
        }
    } else {
        echo "Please provide a valid category name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <title>Edit Category</title>
   

</head>
<body>
   

    <div class="form-container">
        <h1>Edit Category</h1>
        <form action="" method="post">
            <label for="categories_name">Category Name:</label>
            <input type="text" id="categories_name" name="categories_name" value="<?php echo htmlspecialchars($categories_name); ?>" required>
            <button type="submit">Update Category</button>
        </form>
    </div>

    <style>
        body {
            background: url('3.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .form-container h1 {
            margin-bottom: 20px;
            color: #444;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 1rem;
            font-weight: bold;
        }
        input {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            font-size: 1rem;
            color: #fff;
            background-color: #FF6347;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #FF4500;
        }
        header {
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .logout {
            text-decoration: none;
            color: #FF6347;
            font-size: 1.2rem;
        }
        .logout:hover {
            color: #FF4500;
        }
    </style>
</body>
</html>
