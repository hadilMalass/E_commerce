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
        <div class="search-bar">
        <form method="POST" action="">
    <input type="text" name="query" placeholder="Search for products..." value="<?php echo isset($_POST['query']) ? $_POST['query'] : ''; ?>">
    <button type="submit" name="search">
        <i class="fas fa-search"></i> <!-- Search Icon -->
    </button>
    <style>
        /* General Styling for the Search Bar Container */
        .search-bar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px;
        }

        /* Form Styling */
        .search-bar form {
            display: flex;
            align-items: center;
           
            max-width: 600px; /* Adjust as needed */
            background-color: #f9f9f9;
           
        }

        /* Input Field Styling */
        .search-bar input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            font-size: 16px;
            outline: none;
        }

        .search-bar input[type="text"]::placeholder {
            color: #bbb;
        }

        .search-bar button {
            height: 45px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 0 30px 30px 0;
            padding: 0 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .search-bar button:hover {
            background-color: #333;
        }

        /* Icon Styling */
        .search-bar i {
            font-size: 16px;
        }
    </style>
</form>
        </div>
    
        <!-- Navigation Bar -->
       
            <!-- Icons -->
            <div class="header-icons">
                <a href="login1.php" title="Login"><i class="fas fa-user"></i></a>
                <!-- <a href="favorites2.php" title="Favorites"><i class="fas fa-heart"></i></a> -->
                <a href="buyproduct.php" title="Cart"><i class="fas fa-shopping-cart"></i></a>
            </div>  
            <div class="nav-bar">
                <a href="home.php" >Home</a>
                <a href="girl.php">Girls</a>
                <a href="boy.php">Boys</a>
                <a href="sportWear.php">SportWear</a>
                <a href="sale.php" class="active">Sale</a>
                <a href="freeClothes.php">Free Clothes</a>
                <!-- <a href="#">Donation</a> -->
            </div>
    </header>
    
  <style>

/* Navbar Container */
.nav-bar {
    position: absolute; /* Change from fixed to absolute to fit the layout */
    top: 15%; /* Adjust the positioning */
    left: 57%;
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
  <body>
    
  
    <style>
       
    </style>

 <body>
 <div class="banner">
        <div class="left">
            <h1>Super<br><span>Prices</span></h1>
        </div>
        <div class="right">
            <h2>Up to 80% Off</h2>
            <p>Best Deal on Clothes: Girls, Boys, Sport Wear</p>
        </div>
    </div>
    <div class="note">Hurry! Limited Time Offer</div>
    <div id="sale-items">   
 </body>
        <?php
        // Connect to the database
        require_once 'connection.php';
        $search_query = "";
        if (isset($_POST['search']) && !empty($_POST['query'])) {
            $search_query = mysqli_real_escape_string($conn, $_POST['query']);
        }
        // Fetch sales data along with product images and discounted price
        $sql = "
            SELECT 
                sale.sale_id, 
                sale.product_id, 
                sale.Start_sale_date, 
                sale.End_sale_date, 
                sale.`discount%`, 
                product.image, 
                product.product_name, 
                product.description, 
                product.price, 
                ROUND(product.price * (1 - sale.`discount%` / 100), 2) AS discounted_price
            FROM 
                sale 
            INNER JOIN 
                product 
            ON 
                sale.product_id = product.product_id
            WHERE 
                NOW() BETWEEN FROM_UNIXTIME(sale.Start_sale_date) AND FROM_UNIXTIME(sale.End_sale_date)
                 AND (product.product_name LIKE '%$search_query%' OR product.description LIKE '%$search_query%')

        ";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='sale-item'>
                    <div class='badge'>OFFER</div>
                    <img src='productImage/{$row['image']}' alt='Product Image'>
                    <img src='images/logo.png' class='vegan-icon' alt='Vegan'>
                    <h2>{$row['description']}</h2>
                    <p class='discounted-price'>{$row['discounted_price']} $</p>
                    <p class='original-price'>{$row['price']} $</p>
                    <p class='discount'>-{$row['discount%']}%</p>
            <a href='viewSaleItem.php?id={$row['product_id']}' class='view-item-button'>View Item</a>
                </div>";
            }
        } else {
            echo "<p>No sales available at the moment.</p>";
        }

        $conn->close();
        ?>
    
    </div>
</div>
<style>
    .view-item-button {
    display: inline-block;
    background-color: #ff3366; /* Button color */
    color: white; /* Text color */
    padding: 10px 20px; /* Padding around the text */
    font-size: 16px; /* Font size */
    font-weight: bold; /* Make the text bold */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline from the link */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Hover effect */
.view-item-button:hover {
    background-color: #ff6699; /* Slightly lighter color on hover */
    transform: translateY(-2px); /* Button moves slightly up on hover */
}

/* Active state when the button is clicked */
.view-item-button:active {
    background-color: #e6004d; /* Darker shade when clicked */
    transform: translateY(0); /* Reset movement */
}

/* Focus style for accessibility */
.view-item-button:focus {
    outline: 2px solid #ff6699; /* Outline when focused */
    outline-offset: 2px; /* Space between the outline and the button */
}

    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.sale-container {
    width: 100%;
    text-align: center;
    padding: 20px;
}

h1 {
    color: #8B0000;
    margin-bottom: 20px;
}

#sale-items {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.sale-item {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    width: 280px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: left;
    position: relative;
}

.sale-item:hover {
    transform: scale(1.05);
    transition: transform 0.2s;
}

.sale-item img {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 15px;
}

.sale-item .badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: pink;
    color: white;
    font-size: 12px;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
}

.sale-item .vegan-icon {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: auto;
}

.sale-item h2 {
    color: #333;
    font-size: 16px;
    margin: 10px 0 5px;
}

.sale-item p {
    font-size: 14px;
    color: #666;
    margin: 5px 0;
}

.sale-item .original-price {
    text-decoration: line-through;
    color: #999;
    font-size: 14px;
}

.sale-item .discounted-price {
    color: #8B0000;
    font-weight: bold;
    font-size: 18px;
}

.sale-item .discount {
    font-size: 14px;
    color: #8B0000;
    font-weight: bold;
}
body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .banner {
            width: 100%;
            height: 200px;
            background: linear-gradient(90deg, #b00000 50%, #e00000 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 50px;
            box-sizing: border-box;
        }

        .banner .left {
            text-transform: uppercase;
        }

        .banner .left h1 {
            font-size: 50px;
            margin: 0;
            font-weight: bold;
        }

        .banner .left h1 span {
            color: darkorange; /* Bright yellow */
        }

        .banner .right {
            text-align: right;
        }

        .banner .right h2 {
            font-size: 60px;
            margin: 0;
            font-weight: bold;
            color: darkorange; /* Bright yellow */
        }

        .banner .right p {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: normal;
            text-transform: uppercase;
        }

        .note {
            text-align: center;
            background: white;
            color: #b00000;
            font-weight: bold;
            padding: 10px 0;
            position: relative;
            top: -20px;
        }
</style>
</body>

</html>

    
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
                    
