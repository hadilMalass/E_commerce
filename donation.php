<?php
require_once 'connection.php';

// Initialize variables
$submitted = false;
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $phone = htmlspecialchars($_POST['phone']);

    // Validate phone number (must be 8 digits)
    if (preg_match('/^\d{8}$/', $phone)) {
        // Insert data into the donation table
        $stmt = $conn->prepare("INSERT INTO donation (donation_name, donation_number) VALUES (?, ?)");
        $stmt->bind_param("si", $username, $phone);

        if ($stmt->execute()) {
            $submitted = true;
        } else {
            $error = "Error saving donation: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Phone number must be exactly 8 digits.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Lilia Store Donation</title>
    <header>
    <a href="freeClothes.php" class="logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </header>
    <style>
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #333;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .logout i {
            font-size: 1.2rem;
            color: black; /* Make the icon stand out */
        }
        .logout:hover {
            color: black;
            transition: 0.3s;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f8f8;
        }
        .container {
            display: flex;
            width: 80%;
            max-width: 900px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .form-section {
            flex: 1;
            padding: 20px;
            background: #fff;
        }
        .form-section h3 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .form-section label {
            display: block;
            margin: 10px 0 5px;
            color: #666;
        }
        .form-section input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-section button {
            width: 100%;
            padding: 10px;
            background: black;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-section button:hover {
            background: #333;
        }
        .thank-you-section {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: #fff7f9;
        }
        .thank-you-section h1 {
            font-size: 36px;
            color: #e91e63;
        }
        .thank-you-section p {
            font-size: 18px;
            color: #333;
        }
        .thank-you-section img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Form Section -->
        <div class="form-section">
            <?php if (!$submitted): ?>
                <h3>Donate to Lilia Store</h3>
                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <form method="POST" action="">
                    <label for="username">User Name</label>
                    <input type="text" id="username" name="username" placeholder="Enter your name" required>

                    <label for="phone">Phone Number To contact you:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>

                    <button type="submit">I want to donate</button>
                </form>
            <?php else: ?>
                <h3>Thank you for your donation!</h3>
            <?php endif; ?>
        </div>

        <!-- Thank-You Section -->
        <div class="thank-you-section">
            <?php if ($submitted): ?>
                <img src="images\logo.png" alt="Lilia Store Logo">
                <h1>LILIA Store</h1>
                <p>Thanks <strong><?php echo $username; ?></strong></p>
                <p>For your donation</p>
            <?php else: ?>
                <img src="images\logo.png" alt="Lilia Store Logo">
                <h1>LILIA Store</h1>
                <p>Your donation makes a difference!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
