<?php
session_start();
require_once 'connection.php';

// Check if the "Add to Favorites" form was submitted
if (isset($_POST['add_to_favoritesSale'])) {
    $productId = $_POST['product_id'];

    // Validate the product ID
    if (empty($productId) || !is_numeric($productId)) {
        $_SESSION['error'] = "Invalid product ID!";
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
        LEFT JOIN 
            sale s 
        ON 
            p.product_id = s.product_id
        WHERE 
            p.product_id = ? 
            AND (s.product_id IS NULL OR NOW() BETWEEN FROM_UNIXTIME(s.Start_sale_date) AND FROM_UNIXTIME(s.End_sale_date))
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        // Use the discounted price if available, otherwise use the original price
        $price = $product['discounted_price'] ?: $product['price'];

        $favoriteItem = [
            'id' => $product['product_id'],
            'name' => $product['product_name'],
            'price' => $price,
            'image' => $product['image']
        ];

        // Check if the favorites session already exists
        if (!isset($_SESSION['favoritesSale'])) {
            $_SESSION['favoritesSale'] = [];
        }

        // Check if the product is already in favorites
        $exists = false;
        foreach ($_SESSION['favorites'] as $item) {
            if ($item['id'] === $favoriteItem['id']) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $_SESSION['favoritesSale'][] = $favoriteItem;
        }

        // Insert into the favorites table
        $stmt = $conn->prepare("
            INSERT INTO favorites (id, name, price, image) 
            VALUES (?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE price = VALUES(price), image = VALUES(image)
        ");
        $stmt->bind_param(
            "isds",
            $favoriteItem['id'],
            $favoriteItem['name'],
            $favoriteItem['price'],
            $favoriteItem['image']
        );
        $stmt->execute();
        $stmt->close();
    } else {
        // Product not found or not available for sale
        $_SESSION['error'] = "This product is not available at the moment.";
        header("Location: viewSaleItem.php?id=$productId");
        exit();
    }

    // Redirect back to the home page or product page
    header('Location: home.php');
    exit();
}
?>
