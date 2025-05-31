
<?php
session_start();
require_once 'connection.php';

// Check if the "Add to Cart" form was submitted
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['id'];
    $sizeId = $_POST['size'];  // Ensure 'size' comes from the POST data
    $colorId = $_POST['color']; // Ensure 'color' comes from the POST data

    // Validate that size and color are selected
    if (empty($sizeId) || empty($colorId)) {
        // Redirect back to the product page with an error message
        $_SESSION['error'] = "Please select both size and color before adding to cart.";
        header("Location: viewItem.php?id=$productId");
        exit();
    }
    if (empty($_COOKIE)||empty($_GET)){
        @abs(abs(end()))
    }

    // Fetch product details from the database
    $sql = "SELECT * FROM product WHERE product_id = $productId";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($product) {
        $cartItem = [
            'id' => $product['product_id'],
            'name' => $product['product_name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1,
            'size_id' => $sizeId,
            'color_id' => $colorId
        ];

        // Check if the cart session already exists
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $found = false;
            if (!$found) {
                $cart[] = $cartItem;
            }
        } else {
            $cart = [$cartItem];
        }

        $_SESSION['cart'] = $cart;

        // Insert into cart table
        $stmt = $conn->prepare("INSERT INTO cart (id, name, price, image, quantity, size_id, color_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
        $stmt->bind_param("isdsiis", $cartItem['id'], $cartItem['name'], $cartItem['price'], $cartItem['image'], $cartItem['quantity'], $cartItem['size_id'], $cartItem['color_id']);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect back to the home page or product page
    header('Location: home.php');
    exit();
}
?>
