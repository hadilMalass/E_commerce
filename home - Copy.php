

<html>
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
                <a href="home.php" class="active">Home</a>
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
    top: 15%; /* Adjust the positioning */
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
    <body >
    <img src="images/background.jpg" alt="background"class="background">

       <?php
       
       require_once 'connection.php';

// Initialize query and filters
$filters = [];
$sql = "SELECT DISTINCT p.* FROM product p 
        LEFT JOIN product_colors pc ON p.product_id = pc.product_id
        LEFT JOIN product_size ps ON p.product_id = ps.product_id
        WHERE 1=1";

// Apply filters based on user selection
if (isset($_GET['price'])) {
    $price = intval($_GET['price']);
    $filters[] = "p.price <= $price";
}

if (!empty($_GET['color'])) {
    $colors = array_map('intval', $_GET['color']);
    $color_placeholders = implode(",", array_fill(0, count($colors), "?"));
    $filters[] = "pc.color_id IN ($color_placeholders)";
}

if (!empty($_GET['size'])) {
    $sizes = array_map('intval', $_GET['size']);
    $size_placeholders = implode(",", array_fill(0, count($sizes), "?"));
    $filters[] = "ps.size_id IN ($size_placeholders)";
}

// Add filters to SQL query
if (!empty($filters)) {
    $sql .= " AND " . implode(" AND ", $filters);
}
if (isset($_POST['search']) && !empty($_POST['query'])) {
    $search_query = mysqli_real_escape_string($conn, $_POST['query']);
    $sql .= " AND (p.product_name LIKE '%$search_query%' OR p.description LIKE '%$search_query%')";
}
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$bind_types = '';
$params = [];
if (!empty($_GET['color'])) {
    $bind_types .= str_repeat('i', count($colors));
    $params = array_merge($params, $colors);
}
if (!empty($_GET['size'])) {
    $bind_types .= str_repeat('i', count($sizes));
    $params = array_merge($params, $sizes);
}
if ($bind_types) {
    $stmt->bind_param($bind_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$products = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="main-container">

<!-- Filter Section -->
    <div class="filter-container">
        <form method="GET" action="">
            <h3>Filter</h3>
            
            <!-- Price Range -->
            <div class="filter-section">
                <label for="price">Price:</label>
                <input type="range" id="price" name="price" min="0" max="200" 
                       value="<?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : 200; ?>"
                       oninput="this.nextElementSibling.value = this.value">
                <output><?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : 200; ?></output>
            </div>
            
            <!-- Color Filter -->
            <div class="filter-section">
                <label>Color:</label>
                <?php
                $color_query = "SELECT DISTINCT c.color_id, c.name_color FROM color c
                                INNER JOIN product_colors pc ON c.color_id = pc.color_id";
                $colors = $conn->query($color_query);
                if ($colors->num_rows > 0) {
                    while ($color = $colors->fetch_assoc()) {
                        $checked = isset($_GET['color']) && in_array($color['color_id'], $_GET['color']) ? 'checked' : '';
                        echo "<div><input type='checkbox' name='color[]' value='{$color['color_id']}' $checked> {$color['name_color']}</div>";
                    }
                }
                ?>
            </div>
            
            <!-- Size Filter -->
            <div class="filter-section">
                <label>Size:</label>
                <?php
                $size_query = "SELECT DISTINCT s.size_id, s.age_size FROM size s
                               INNER JOIN product_size ps ON s.size_id = ps.size_id";
                $sizes = $conn->query($size_query);
                if ($sizes->num_rows > 0) {
                    while ($size = $sizes->fetch_assoc()) {
                        $checked = isset($_GET['size']) && in_array($size['size_id'], $_GET['size']) ? 'checked' : '';
                        echo "<div><input type='checkbox' name='size[]' value='{$size['size_id']}' $checked> {$size['age_size']}</div>";
                    }
                }
                ?>
            </div>
            
            <!-- Submit Button -->
            <button type="submit">Apply Filters</button>
        </form>
    </div>

    <!-- Product Grid -->
    <div class="product-container">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="productImage/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['description']); ?>">
                    <h2><?php echo htmlspecialchars($product['description']); ?></h2>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="viewItem.php?id=<?php echo $product['product_id']; ?>">View item</a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
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
                    

</html>