<?php

session_start();
require_once 'connection.php';
if (!isset($_SESSION['isloggedin']) || $_SESSION['isloggedin'] != 1) {
    header("Location: login1.php");
    exit();
}
if(isset($_POST['update_update_btn'])){
   $update_value = (int)$_POST['update_quantity']; // Ensure the value is an integer
   $update_id = (int)$_POST['update_quantity_id']; // Ensure the ID is an integer
   $update_quantity_query = mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_value' WHERE id = '$update_id'");
   if($update_quantity_query){
      header('location:buyproduct.php');
   }
}

if(isset($_GET['remove'])){
   $remove_id = (int)$_GET['remove']; // Ensure the ID is an integer
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'");
   header('location:buyproduct.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart`");
   header('location:buyproduct.php');

   

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style3.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i></div>';
   };
};


?>

<header class="header">
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
                <a href="home.php" >Home</a>
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
</header>

<div class="container">

<section class="shopping-cart">

<h1 class="heading">shopping cart</h1>

<table>
   <thead>
   
      <th>image</th>
      <th>name</th>
      <th>size</th>
      <th>color</th>
      <th>price</th>
      <th>quantity</th>
      <th>total price</th>
      <th>action</th>
   </thead>

   <body>
   <?php 
   
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
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
         $image = intval($fetch_cart['image']);
         
         if($price && $quantity){
            $sub_total = $price * $quantity;
         } else {
            $sub_total = 0; // Handle non-numeric values gracefully
         }

         $grand_total += $sub_total;
         $image= 'productImage/' . $fetch_cart['image'];
         
   ?>
      <tr>
      <td><img src="<?php echo $image; ?>" alt="Product Image"></td>
         <td><?php echo $fetch_cart['name']; ?></td>
        <td> <?php echo $fetch_cart['age_size']; ?></td>
        <td> <?php echo $fetch_cart['name_color']; ?></td>
         <td><?php echo number_format($price, 2); ?>$</td>
         <td>
            <form action="" method="post">
               <input type="hidden" name="update_quantity_id" value="<?php echo $fetch_cart['id']; ?>">
               <input type="number" name="update_quantity" min="1" value="<?php echo $quantity; ?>">
               <input type="submit" value="update" name="update_update_btn">
            </form>   
         </td>
         <td><?php echo number_format($sub_total, 2); ?>$</td>
         <td><a href="buyproduct.php?remove=<?php echo $fetch_cart['id']; ?>" onclick="return confirm('remove item from cart?')" class="delete-btn"> <i class="fas fa-trash"></i> remove</a></td>
      </tr>
   <?php
      }
   }
   ?>

   <tr class="table-bottom">
      <td><a href="home.php" class="option-btn" style="margin-top: 0;">continue shopping</a></td>
      <td colspan="2">grand total</td>
      <td><?php echo number_format($grand_total, 2); ?>$</td>
      <td><a href="buyproduct.php?delete_all" onclick="return confirm('are you sure you want to delete all?');" class="delete-btn"> <i class="fas fa-trash"></i> delete all </a></td>
   </tr>

   </body>
</table>
<div class="checkout-btn">
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">procced to checkout</a>
   </div>
</section>

</div>

<!-- custom js file link  -->
<script > 
    let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.header .navbar');

menu.onclick = () =>{
   menu.classList.toggle('fa-times');
   navbar.classList.toggle('active');
};

window.onscroll = () =>{
   menu.classList.remove('fa-times');
   navbar.classList.remove('active');
};


document.querySelector('#close-edit').onclick = () =>{
   document.querySelector('.edit-form-container').style.display = 'none';
   window.location.href = 'admin.php';
};
</script>

</body>
</html>
<style>
   body {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   box-sizing: border-box;
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

.shopping-cart table {
   border-color: white;
   width: 100%;
   border-collapse: collapse;
   margin-bottom: 2rem;
   background-color: #fff;
   border-radius: 8px;
   overflow: hidden;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.shopping-cart table img{
   width: 100px;
}
.shopping-cart thead {
   background-color: pink;
   color: #fff;
   text-transform: uppercase;
}

.shopping-cart th, .shopping-cart td {
   padding: 1rem;
   text-align: center;
   font-size: 1rem;
   border: 1px solid #ddd;
}

.shopping-cart td {
   background-color: #fdfdfd;
   color: #333;
}

.shopping-cart .delete-btn {
   color: #fff;
   background-color: black;
   padding: 0.5rem 1rem;
   border: none;
   border-radius: 4px;
   text-transform: uppercase;
   font-size: 0.9rem;
   cursor: pointer;
   transition: 0.3s;
   text-decoration: none;
}

.shopping-cart .delete-btn:hover {
   background-color: darkblue;
}

.shopping-cart .option-btn {
   color: #fff;
   background-color: black;
   padding: 0.5rem 1rem;
   border: none;
   border-radius: 4px;
   text-transform: uppercase;
   font-size: 0.9rem;
   cursor: pointer;
   transition: 0.3s;
   text-decoration: none;
}

.shopping-cart .option-btn:hover {
   background-color: darkblue;
}

.checkout-btn {
   text-align: center;
   margin: 1rem 0;
}

.checkout-btn .btn {
   background-color: black;
   color: #fff;
   padding: 0.75rem 1.5rem;
   font-size: 1rem;
   border-radius: 4px;
   text-transform: uppercase;
   border: none;
   cursor: pointer;
   transition: 0.3s;
   text-decoration: none;
}

.checkout-btn .btn:hover {
   background-color: darkblue;
}

.checkout-btn .btn.disabled {
   background-color: #ccc;
   cursor: not-allowed;
}

.quantity-input {
   width: 60px;
   padding: 0.5rem;
   font-size: 1rem;
   text-align: center;
   border: 1px solid #ddd;
   border-radius: 4px;
}

.table-bottom td {

   font-weight: bold;
   background-color: pink;
   color: white;
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
            
</footer>