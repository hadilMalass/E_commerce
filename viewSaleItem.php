<?php
session_start();
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['isloggedin']) || $_SESSION['isloggedin'] != 1) {
    header("Location: login1.php");
    exit();
}

// Fetch product details if an ID is provided
$product = null;
$sizes = [];
$colors = [];
$new_price = null;

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']); // Ensure the ID is an integer

    // Fetch product details
    $sql = "SELECT * FROM product WHERE product_id = $productId";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Fetch sizes specific to this product
        $sizes = $conn->query("
            SELECT s.size_id, s.age_size 
            FROM product_size ps 
            INNER JOIN size s ON ps.size_id = s.size_id 
            WHERE ps.product_id = $productId
        ");

        // Fetch colors specific to this product
        $colors = $conn->query("
            SELECT c.color_id, c.name_color 
            FROM product_colors pc 
            INNER JOIN color c ON pc.color_id = c.color_id 
            WHERE pc.product_id = $productId
        ");

        // Fetch sale details for this product (if active)
        $sale = $conn->query("
            SELECT `discount%` 
            FROM sale 
            WHERE product_id = $productId 
            AND UNIX_TIMESTAMP() BETWEEN Start_sale_date AND End_sale_date
        ");

        if ($sale && $sale->num_rows > 0) {
            $sale_data = $sale->fetch_assoc();
            $discount = $sale_data['discount%'];
            $new_price = $product['price'] - ($product['price'] * ($discount / 100));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="filter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title><?php echo htmlspecialchars($product['product_name'] ?? "View Item"); ?></title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .logo {
            height: 140px;
        }

        .product-details {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            gap: 20px;
        }

        .product-details img {
            width: 400px;
            border-radius: 10px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            max-width: 600px;
        }

        .price {
            font-size: 24px;
            color: #333;
        }

        .price .original-price {
            text-decoration: line-through;
            color: #999;
            margin-right: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form button {
            background-color: #000;
            color: #fff;
            font-size: 18px;
            padding: 12px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
   
    <header>
        <img src="images/image.png" alt="Logo" class="logo">
        <!-- <div class="search-bar">
            <input type="text" placeholder="Search">
            <a href="search.php"><i class="fas fa-search"></i></a>
        </div> -->
        <div class="header-icons">
            <a href="login1.php"><i class="fas fa-user"></i></a>
            <!-- <a href="favorites2.php"><i class="fas fa-heart"></i></a> -->
            <a href="buyproduct.php"><i class="fas fa-shopping-cart"></i></a>
        </div>
        <div class="nav-bar">
            <a href="home.php" class="active">Home</a>
            <a href="girl.php">Girls</a>
            <a href="boy.php">Boys</a>
            <a href="sportWear.php">SportWear</a>
            <a href="sale.php">Sale</a>
            <a href="freeClothes.php">Free Clothes</a>
        </div>
    </header>
    

    <?php if ($product): ?>
        <div class="product-details">
            <img src="productImage/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['description']); ?>">
            <div class="product-info">
                <h2><?php echo htmlspecialchars($product['description']); ?></h2>
                <p class="price">
                    <?php if ($new_price !== null): ?>
                        <span class="original-price">$<?php echo htmlspecialchars($product['price']); ?></span>
                        <span class="sale-price">$<?php echo number_format($new_price, 2); ?></span>
                    <?php else: ?>
                        $<?php echo htmlspecialchars($product['price']); ?>
                    <?php endif; ?>
                </p>
                <form method="POST" action="addToCartSale.php">
                    <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                    <label for="size">Size:</label>
                    <select name="size" id="size" required>
                        <option value="">Select a size</option>
                        <?php while ($size = $sizes->fetch_assoc()): ?>
                            <option value="<?php echo $size['size_id']; ?>"><?php echo htmlspecialchars($size['age_size']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="color">Color:</label>
                    <select name="color" id="color" required>
                        <option value="">Select a color</option>
                        <?php while ($color = $colors->fetch_assoc()): ?>
                            <option value="<?php echo $color['color_id']; ?>"><?php echo htmlspecialchars($color['name_color']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="add_to_cart">Add To Cart</button>
                </form>
                <!-- <form method="POST" action="favoritesSale.php">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <button type="submit" class="favorite-button" name="add_to_favoritesSale">
                <i class="fas fa-heart"></i> 
            </button>
        </form> -->
            </div>
        </div>
    <?php else: ?>
        <p>Product not found.</p>
    <?php endif; ?>

   
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
                            <a href="#">About Us</a>
                            <span>|</span>
                            <a href="#">Our Store</a>
                            <span>|</span>
                            <a href="#">Contact Us</a>
    
                        </div>
                
                        <div>
                            <a href="#"><img src="images/fbicon.png" alt="Facebook Icon"></a>
                            <a href="#"><img src="images/instaicon.png" alt="Instagram Icon"></a>
                        </div>
                    </div>
                </footer>
</body>
</html>
