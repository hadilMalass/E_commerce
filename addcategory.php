<?php
session_start();

// Ensure the user is logged in and is an admin
if ($_SESSION['isloggedin'] != 1 || $_SESSION['role_id'] != 1) {
    header("Location: login1.php");
    exit();
}

require_once 'connection.php';

// Handle form submission
if (isset($_POST['categories_name']) && !empty($_POST['categories_name'])) {
    // Escape the input to prevent SQL injection
    $categories_name = $conn->real_escape_string($_POST['categories_name']);

    // Insert the new category into the `categories` table
    $sql = "INSERT INTO categories (categories_name) VALUES ('$categories_name')";
    $result = $conn->query($sql);

    if ($result) {
        echo "Category added successfully!";
    } else {
        die('Error inserting category: ' . $conn->error);
    }
}
?>

<!-- HTML form for adding categories -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="add_categories.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Add Category</title>
</head>
<body>
<header>
    <img src="images/image.png" alt="Logo" class="logo">
    <a href="admin.php" class="logout">
        <i class="fas fa-sign-out-alt"></i>
    </a>
</header>

<div class="title">
    <h1>Add Category</h1>
</div>
<form action="" method="post">
    <table>
        <tr>
            <td>Category Name:</td>
            <td><input type="text" name="categories_name" required></td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="submit">Add Category</button>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
<style>
    /* General styles */


/* Header */


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
