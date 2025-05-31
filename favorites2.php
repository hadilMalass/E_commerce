<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['isloggedin']) || $_SESSION['isloggedin'] != 1) {
    header("Location: login1.php");
    exit();
}

// Remove a single item from favorites
if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM `favorites` WHERE id = '$remove_id'");
    header('location:favorites2.php');
}

// Remove all items from favorites
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `favorites`");
    header('location:favorites2.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="filter.css">

    <header>
        <img src="images/image.png" alt="Logo" class="logo">
       
        <!-- Navigation Bar -->
       
            <!-- Icons -->
            <div class="header-icons">
                <a href="login1.php" title="Login"><i class="fas fa-user"></i></a>
                <a href="favorites2.php" title="Favorites"><i class="fas fa-heart"></i></a>
                <a href="buyproduct.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
            </div>  
            <div class="nav-bar">
                <a href="home.php">Home</a>
                <a href="girl.php">Girls</a>
                <a href="boy.php">Boys</a>
                <a href="sportWear.php">SportWear</a>
                <a href="sale.php">Sale</a>
                <a href="freeClothes.php">Free Clothes</a>
                <!-- <a href="#">Donation</a> -->
            </div>
    </header>
    
  <style>

/* Navbar Container */
.nav-bar {
    position: absolute; /* Change from fixed to absolute to fit the layout */
    top: 10%; /* Adjust the positioning */
    left: 55%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px; /* Add space between items */
    padding: 5px 15px;
    background-color: #fff;
    border-radius: 20px;
    z-index: 1000;
}

/* Navbar Links */
.nav-bar a {
    text-decoration: none;
    font-size: 16px;
    color: #333;
    padding: 10px 20px;
    border-radius: 20px;
    transition: background-color 0.3s, color 0.3s;
}

/* Hover Effect */
.nav-bar a:hover {
    background-color: #ffebef;
    color: #ff3366;
}

/* Active Link */
.nav-bar .active {
    background-color: #ffe6eb;
    color: #ff3366;
    font-weight: bold;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-bar {
        flex-wrap: wrap; /* Adjust navbar links to stack */
        padding: 10px 20px;
        gap: 15px;
    }

    .nav-bar a {
        font-size: 14px; /* Smaller font size for mobile screens */
        padding: 8px 15px;
    }
}

  </style>
<div class="container">
    <section class="favorites">
        <h1 class="heading">Your Favorites</h1>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch favorites from the database
                $select_favorites = mysqli_query($conn, "SELECT * FROM `favorites`");
                if (mysqli_num_rows($select_favorites) > 0) {
                    while ($fetch_favorites = mysqli_fetch_assoc($select_favorites)) {
                        $price = floatval($fetch_favorites['price']);
                        $image = 'productImage/' . $fetch_favorites['image'];
                ?>
                        <tr>
                            <td><img src="<?php echo $image; ?>" alt="Product Image" style="width: 100px;"></td>
                            <td><?php echo $fetch_favorites['name']; ?></td>
                            <td><?php echo number_format($price, 2); ?>$</td>
                            <td>
                            <a href="favorites2.php?remove=<?php echo $fetch_favorites['id']; ?>" onclick="return confirm('remove item from favorites?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a>

                                <a href="viewItem.php?id=<?php echo $fetch_favorites['id']; ?>" class="view-btn">Add Product</a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align: center;">No items in favorites.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="checkout-btn">
            <a href="favorites2.php?delete_all" 
               onclick="return confirm('Are you sure you want to delete all items from favorites?');" 
               class="btn delete-btn">
               <i class="fas fa-trash"></i> Delete All
            </a>
        </div>
    </section>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .heading {
        font-size: 2.5rem;
        text-align: center;
        color: #333;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
    }

    .favorites table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .favorites table img {
        width: 100px;
    }

    .favorites thead {
        background-color: pink;
        color: #fff;
        text-transform: uppercase;
    }

    .favorites th, .favorites td {
        padding: 1rem;
        text-align: center;
        font-size: 1rem;
        border: 1px solid #ddd;
    }

    .favorites td {
        background-color: #fdfdfd;
        color: #333;
    }

    .delete-btn {
        color: #fff;
        background-color: black;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-transform: uppercase;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.3s;
    }

    .delete-btn:hover {
        background-color: darkblue;
    }

    .checkout-btn .btn {
        background-color: black;
        color: #fff;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        border-radius: 4px;
        text-transform: uppercase;
        border: none;
        text-decoration: none;
        transition: 0.3s;
    }

    .checkout-btn .btn:hover {
        background-color: darkblue;
    }
</style>


<footer>
                <link rel="stylesheet" href="footer.css">
                
                    <div class="footer">
                        <style>
                            .footer {
                                display: flex;
                                flex-direction: column;
                                align-items: center;   /* Center horizontally */
                                justify-content: center; /* Center vertically if needed */
                                text-align: center;
                                background-color: #333; /* Optional: Background color for the footer */
                                padding: 20px 0;        /* Optional: Vertical padding */
                                color: white;
                            }
                
                            .footer2 a {
                                color: white;
                                display: inline-block;
                                text-decoration: none;
                                margin: 0 10px;         /* Add spacing between links */
                            }
                
                            .footer img {
                                width: 24px;            /* Example size for icons */
                                height: 24px;
                                margin: 10px 5px;       /* Add spacing around icons */
                            }
                
                            h1 {
                                margin-bottom: 10px;
                            }
                        </style>
                
                       
                <h1>LILIA STORE</h1>
                
                <div class="footer2">
                    <a href="aboutus.html">About Us</a>
                   
                </div>
        
                <div>
                    <a href="https://www.instagram.com/liliastore.lb"><img src="images/fbicon.png" alt="Facebook Icon"></a>
                    <a href="https://www.instagram.com/liliastore.lb"><img src="images/instaicon.png" alt="Instagram Icon"></a>
                </div>
            </div>
                </footer>
</body>
</html>
