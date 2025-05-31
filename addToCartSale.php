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
        header("Location: viewSaleItem.php?id=$productId");
        exit();
    }

    // Fetch product details along with sale information
    $sql = "
        SELECT 
            p.product_id, 
            p.product_name, 
            p.price, 
            p.image, 
            ROUND(p.price * (1 - s.`discount%` / 100), 2) AS discounted_price
        FROM 
            product p
        INNER JOIN 
            sale s 
        ON 
            p.product_id = s.product_id
        WHERE 
            p.product_id = ? 
            AND NOW() BETWEEN FROM_UNIXTIME(s.Start_sale_date) AND FROM_UNIXTIME(s.End_sale_date)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Use the discounted price if available
        $price = $product['discounted_price'];

        $cartItem = [
            'id' => $product['product_id'],
            'name' => $product['product_name'],
            'price' => $price, // Store the discounted price in the cart
            'image' => $product['image'],
            'quantity' => 1,
            'size_id' => $sizeId,
            'color_id' => $colorId
        ];

        // Check if the cart session already exists
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $found = false;

            // Check if the product with the same size and color already exists in the cart
            foreach ($cart as &$item) {
                if ($item['id'] == $cartItem['id'] && $item['size_id'] == $cartItem['size_id'] && $item['color_id'] == $cartItem['color_id']) {
                    $item['quantity'] += 1; // Increment the quantity
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart[] = $cartItem;
            }
        } else {
            $cart = [$cartItem];
        }

        $_SESSION['cart'] = $cart;

        // Insert into the cart table
        $stmt = $conn->prepare("
            INSERT INTO cart (id, name, price, image, quantity, size_id, color_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE quantity = quantity + 1
        ");
        $stmt->bind_param(
            "isdsiis",
            $cartItem['id'],
            $cartItem['name'],
            $cartItem['price'],
            $cartItem['image'],
            $cartItem['quantity'],
            $cartItem['size_id'],
            $cartItem['color_id']
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // If the product is not on sale, redirect back with an error message
        $_SESSION['error'] = "This product is not available for sale at the moment.";
        header("Location: viewSaleItem.php?id=$productId");
        exit();
    }

    // Redirect back to the home page or product page
    header('Location: home.php');
    exit();
}
?>
