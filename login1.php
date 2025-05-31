<?php
session_start();
require_once 'connection.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'];
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$lastName = $isLoggedIn ? $_SESSION['last_name'] : '';

?>
<!DOCTYPE html>
<html lang="en">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=1.0">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

        
<header>
  <style>
    .nav-bar {
    position: absolute; /* Change from fixed to absolute to fit the layout */
    top: 10%; /* Adjust the positioning */
    left: 50%;
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
    <img src="images/image.png" alt="Logo" class="logo">
      
    <div class="header-icons">
                <a href="login1.php" title="Login" class="active" ><i class="fas fa-user"></i></a>
                <!-- <a href="favorites2.php" title="Favorites"><i class="fas fa-heart"></i></a> -->
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

<body>
  <?php if (isset($_SESSION['isloggedin']) && $_SESSION['isloggedin']): ?>
            <p class="user-greeting">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 
            <?php echo htmlspecialchars($_SESSION['last_name']); ?></span>            
            
        <?php else: ?>
            <a href="login1.php" class="active"></a>
        <?php endif; ?>
    </div>

  <div class="form-container">
    <form action="login2.php" method="post" class="signup-form" >
      <h1>New To LILIA? Welcome.</h1>
      <h3>Please Create your LILIA account here.</h3>
      <table>
        <tr>
          <td>First Name</td>
          <td><input type="text" name="first_name"></td>
        </tr>
        <tr>
          <td>Last Name</td>
          <td><input type="text" name="last_name"></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type="password" id="password" name="password" required minlength="6" 
               oninput="validatePassword()" placeholder="Password must be more than 5 characters">
</td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type="email" name="email"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" value="Create Account"></td>
        </tr>
      </table>
    </form>
 
</script>
    <form action="hadil.php" method="post" class="signin-form">
      <h1>SIGN IN TO LILIA.</h1>
      <h3>Welcome Back! If you already have an account with us, please sign in.</h3>
      <table>
        <tr>
          <td>Email</td>
          <td><input type="text" name="email"></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type="password" name="password"></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" value="Sign In and Continue"></td>
        </tr>
        <tr>
          <td colspan="2">
            <a href="logout.php">Logout</a>
          </td>
        </tr>
        
      </table>
    </form>
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
                <script>
    // Check if a welcome message should be shown
    const showWelcomeMessage = <?php echo json_encode($showWelcomeMessage); ?>;
    const userName = <?php echo json_encode($userName); ?>;
    const lastName = <?php echo json_encode($lastName); ?>;


    if (showWelcomeMessage && userName) {
        alert(`Welcome, ${userName}! We're glad to see you at LILIA Store.`);
        alert(`Welcome, ${lastName}! We're glad to see you at LILIA Store.`);
    }
</script>
<script>
        function validatePassword() {
            const passwordInput = document.getElementById("password");
            if (passwordInput.value.length <= 5) {
                passwordInput.setCustomValidity("Password must be more than 5 characters.");
            } else {
                passwordInput.setCustomValidity(""); // Clear the custom error
            }
        }
    </script>
                
