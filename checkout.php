<?php
session_start();
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['isloggedin']) || !$_SESSION['isloggedin']) {
    header("Location: login1.php"); // Redirect to login page if not logged in
    exit();
}

// Retrieve the user's full name from the session
$fullName = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest';

if (isset($_POST['order_btn'])) {
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number']; // Capture phone number
    $method = $_POST['method'];
    $country = $_POST['country'];
    
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart`");
    $price_total = 0;
    $product_name = []; // Initialize array to avoid warnings

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['name'] . ' (' . $product_item['quantity'] . ') ';
            $product_price = $product_item['price'] * $product_item['quantity'];
            $price_total += $product_price;
        }
    }

    $total_product = implode(', ', $product_name);
    $detail_query = mysqli_query($conn, 
        "INSERT INTO `order` (name, phone_number, method, country, total_product, total_price)
        VALUES ('$name', '$phone_number', '$method', '$country', '$total_product', $price_total)") 
        or die('Query failed');

    if ($cart_query && $detail_query) {
        $delete_cart_query = mysqli_query($conn, "DELETE FROM `cart`") or die('Failed to clear cart');
        if ($delete_cart_query) {
            echo "
            <div class='order-message-container'>
            <div class='message-container'>
                <h3>Thank you for shopping!</h3>
                <div class='order-detail'>
                    <span>" . $total_product . "</span>
                    <span class='total'>Total: $" . number_format($price_total, 2) . "/-</span>
                </div>
                <div class='customer-details'>
                    <p>Your Name: <span>" . $name . "</span></p>
                    <p>Your Phone: <span>" . $phone_number . "</span></p>
                    <p>Your Address: <span>" . $country . "</span></p>
                    <p>Payment Method: <span>" . $method . "</span></p>
                </div>
                <a href='buyproduct.php' class='btn'>Done</a>
            </div>
            </div>
            ";
        }
    }
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
    <div class="header-icons">
        <a href="login1.php" title="Login"><i class="fas fa-user"></i></a>
        <a href="favorites.php" title="Favorites"><i class="fas fa-heart"></i></a>
        <a href="buyproduct.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
    </div>
    <div class="nav-bar">
        <a href="home.php">Home</a>
        <a href="girl.php">Girls</a>
        <a href="boy.php">Boys</a>
        <a href="sportWear.php">SportWear</a>
        <a href="sale.php">Sale</a>
        <a href="freeClothes.php">Free Clothes</a>
    </div>
</header>

<style>
/* Navbar Styling */
.nav-bar {
    position: absolute;
    top: 9%;
    left: 55%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    padding: 5px 15px;
    background-color: #fff;
    border-radius: 20px;
    z-index: 1000;
}

.nav-bar a {
    text-decoration: none;
    font-size: 16px;
    color: #333;
    padding: 10px 20px;
    border-radius: 20px;
    transition: background-color 0.3s, color 0.3s;
}

.nav-bar a:hover {
    background-color: #ffebef;
    color: #ff3366;
}
</style>

<body>
<div class="container">
<section class="checkout-form">
    <h1 class="heading">Complete Your Order</h1>
    <form action="" method="post">
        <div class="display-order">
            <?php
            $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
            $grand_total = 0;
            if (mysqli_num_rows($select_cart) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                    $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                    $grand_total += $total_price;
            ?>
            <span><?= $fetch_cart['name']; ?> (<?= $fetch_cart['quantity']; ?>)</span>
            <?php
                }
            } else {
                echo "<div class='display-order'><span>Your cart is empty!</span></div>";
            }
            ?>
            <span class="grand-total">Grand Total: $<?= number_format($grand_total, 2); ?>/-</span>
        </div>

        <div class="flex">
            <div class="inputBox">
                <span>Your Name</span>
                <input type="text" placeholder="Enter your name" name="name" 
                       value="<?php echo htmlspecialchars($_SESSION['user_name']); ?> <?php echo htmlspecialchars($_SESSION['last_name']); ?>" required>
            </div>
            <div class="inputBox">
                <span>Phone Number</span>
                <input type="text" placeholder="e.g. +96103345678" name="phone_number" required>
            </div>
            <div class="inputBox">
                <span>Payment Method</span>
                <select name="method">
                    <option value="cash on delivery" selected>Cash on Delivery</option>
                    <option value="Send money for this number 71-43 88 32">OMT</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address</span>
                <input type="text" placeholder="e.g. Tripoli" name="country" required>
            </div>
        </div>
        <input type="submit" value="Order Now" name="order_btn" class="btn">
    </form>
</section>
</div>
</body>
</html>
<style>
    /* General Reset */
body {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    color: #333;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styling */
.header {
    background: #333;
    color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .navbar {
    display: flex;
    gap: 15px;
}

.header a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    transition: 0.3s;
}

.header a:hover {
    color: #ff6f61;
}

/* Checkout Form Section */
.checkout-form {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.checkout-form h1 {
    font-size: 28px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.display-order {
    background: #f9f9f9;
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.display-order span {
    display: block;
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.display-order .grand-total {
    font-size: 20px;
    color: #ff6f61;
    font-weight: bold;
    text-align: right;
    margin-top: 15px;
}

.flex {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.inputBox {
    flex: 1 1 calc(50% - 20px);
    margin-bottom: 20px;
}

.inputBox span {
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
    display: block;
}

.inputBox input,
.inputBox select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    margin-top: 5px;
}

.inputBox input:focus,
.inputBox select:focus {
    border-color: #ff6f61;
    outline: none;
}

/* Button Styling */
.btn {
    display: inline-block;
    background: #ff6f61;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.btn:hover {
    background: #e65a50;
}

.order-message-container {
    background: rgba(0, 0, 0, 0.6);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.message-container {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.message-container h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 15px;
}

.message-container .order-detail,
.message-container .customer-details {
    margin-bottom: 20px;
    text-align: left;
}

.message-container .order-detail span,
.message-container .customer-details p {
    font-size: 16px;
    color: #555;
    display: block;
    margin-bottom: 5px;
}

.message-container a {
    text-decoration: none;
    background: #ff6f61;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 16px;
}

.message-container a:hover {
    background: #e65a50;
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
                    