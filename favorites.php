<section class="shopping-cart">

<h1 class="heading">Shopping Cart</h1>

<div class="cart-grid">
   <?php 
require_once 'connection.php';

   // Updated query to join size and color tables
   $select_cart = mysqli_query($conn, "
      SELECT cart.*, size.age_size, color.name_color 
      FROM `cart` 
      LEFT JOIN `size` ON cart.size_id = size.size_id 
      LEFT JOIN `color` ON cart.color_id = color.color_id
   ");
   
   $grand_total = 0;
   if(mysqli_num_rows($select_cart) > 0){
      while($fetch_cart = mysqli_fetch_assoc($select_cart)){
         $price = floatval($fetch_cart['price']);
         $quantity = intval($fetch_cart['quantity']);
         $sub_total = $price * $quantity;
         $grand_total += $sub_total;
         $image_path = 'productImage/' . $fetch_cart['image'];
   ?>
      <div class="cart-item">
         <img src="<?php echo $image_path; ?>" alt="Product Image">
         <h3><?php echo $fetch_cart['name']; ?></h3>
         <p>Size: <?php echo $fetch_cart['age_size']; ?></p>
         <p>Color: <?php echo $fetch_cart['name_color']; ?></p>
         <p>Price: <?php echo number_format($price, 2); ?>$</p>
         <p>Subtotal: <?php echo number_format($sub_total, 2); ?>$</p>
         <form action="" method="post">
            <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
            <input type="number" name="update_quantity" min="1" value="<?php echo $quantity; ?>" class="quantity-input">
            <input type="submit" value="Update" name="update_update_btn" class="btn">
         </form>
         <a href="buyproduct.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('Remove item from cart?')" class="delete-btn">Remove</a>
      </div>
   <?php
      }
   }
   ?>
</div>

<div class="cart-summary">
   <h2>Grand Total: <?php echo number_format($grand_total, 2); ?>$</h2>
   <a href="buyproduct.php?delete_all" onclick="return confirm('Are you sure you want to delete all?');" class="delete-btn">Delete All</a>
   <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
</div>

</section>

<style>
   .cart-grid {
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
   gap: 1.5rem;
   margin: 2rem 0;
}

.cart-item {
   background-color: var(--bg-color);
   border: var(--border);
   border-radius: .5rem;
   text-align: center;
   padding: 2rem;
   box-shadow: var(--box-shadow);
}

.cart-item img {
   width: auto;
   height: 200px;
   object-fit: cover;
   border-radius: .5rem;
   margin-bottom: 1rem;
}

.cart-item h3 {
   font-size: 2rem;
   color: var(--black);
   margin-bottom: .5rem;
}

.cart-item p {
   font-size: 1.6rem;
   color: var(--black);
   margin: .5rem 0;
}

.cart-item .quantity-input {
   width: 80px;
   padding: .5rem;
   font-size: 1.5rem;
   text-align: center;
   margin: .5rem 0;
}

.cart-summary {
   text-align: center;
   margin-top: 2rem;
}

.cart-summary h2 {
   font-size: 2.5rem;
   margin-bottom: 1rem;
}

</style>
__________________________________________________
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

<section class="checkout-form">

    <h1 class="heading">Complete Your Order</h1>

    <form action="" method="post">

    <div class="display-order">
        <!-- Your cart display code -->
    </div>

    <div class="flex">
        <div class="inputBox">
            <span>Your Full Name</span>
            <input type="text" placeholder="Enter your name" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
        </div>
        <!-- Other input fields -->
    </div>

    <input type="submit" value="Order Now" name="order_btn" class="btn">
    </form>

</section>

</div>

</body>
</html>
__________________________________________________________________
<?php
session_start();
require_once 'connection.php';

// Check if the "Add to Favorites" form was submitted
if (isset($_POST['add_to_favorites'])) {
    $productId = $_POST['product_id']; // Get the product ID from the POST data

    // Ensure productId is not empty or invalid
    if (empty($productId) || !is_numeric($productId)) {
        echo "Invalid product ID!";
        exit();
    }

    // Fetch product details from the database
    $sql = "SELECT * FROM product WHERE product_id = $productId";
    $result = $conn->query($sql);

    // Check if product exists
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();

        $favoriteItem = [
            'id' => $product['product_id'],
            'name' => $product['product_name'],
            'price' => $product['price'],
            'image' => $product['image']
        ];

        // Check if the favorites session already exists
        if (isset($_SESSION['favorites'])) {
            $favorites = $_SESSION['favorites'];
            $found = false;
            foreach ($favorites as $item) {
                if ($item['id'] === $favoriteItem['id']) {
                    $found = true;  // Item already in favorites, no need to add it again
                    break;
                }
            }

            // If not found, add to favorites
            if (!$found) {
                $favorites[] = $favoriteItem;
            }
        } else {
            $favorites = [$favoriteItem];  // Initialize favorites session if it doesn't exist
        }

        $_SESSION['favorites'] = $favorites;

        // Insert into favorites table
        $stmt = $conn->prepare("INSERT INTO favorites (id, name, price, image) 
        VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE id = id");
        $stmt->bind_param("isss", $favoriteItem['id'], $favoriteItem['name'], $favoriteItem['price'], $favoriteItem['image']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Product not found!";
        exit();
    }
} else {
    echo "Product ID not provided!";
    exit();
}

// Redirect back to the home page or product page
header('Location: home.php');
exit();
?>
	
