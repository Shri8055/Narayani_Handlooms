<?php
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<h2>Product not found</h2>";
        exit;
    }
}else{
    echo "<h2>Invalid request</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['product_name']; ?> - Narayani Handlooms</title>
    <link rel="stylesheet" href="product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div id="popup-container" class="popup">
            <div class="popup-content">
              <img class="popup-image" src="images/Narayani-removebg.png" alt="">
                <div class="inner-popup-content">
                  <span class="close">&times;</span>
                  <div class="tabs">
                      <button class="tablinks active" onclick="openTab(event, 'Login')">Login</button>
                      <button class="tablinks" onclick="openTab(event, 'Signup')">Signup</button>
                  </div>
                  <div id="Login" class="tabcontent active">
                      <form action="home.php" method="POST">
                          <label for="user"><i class="fa-solid fa-envelope"></i></label>
                          <input type="email" id="user" name="email" placeholder="Email" required>
                          <label for="pass"><i class="fa-solid fa-key"></i></label>
                          <input type="password" id="pass" name="password" placeholder="Password" required>
                          <a href="#" class="forgot-pass"><p>Forgot password ?</p></a>
                          <button type="submit" name="login" class="login-btn">Login</button>
                      </form>
                  </div>
                  <div id="Signup" class="tabcontent">
                      <form action="home.php" method="POST">
                          <label for="fname"><i class="fa-solid fa-user"></i></label>
                          <input type="text" id="fname" name="name" placeholder="Full Name" required>
                          <label for="email"><i class="fa-solid fa-envelope"></i></label>
                          <input type="email" id="email" name="email" placeholder="Email" required>
                          <label for="pass"><i class="fa-solid fa-key"></i></label>
                          <input type="password" id="pass" name="password" placeholder="Password" required>
                          <button type="submit" name="signup" class="sign-btn">Signup</button>
                      </form>
                  </div>
                </div>
            </div>
        </div>
        <section class="sec1-container">
          <div class="left-inner-sec1-container">
              <a href="#" id="user-icon">
                  <i class="fa-solid fa-user" data-tooltip="Log-in / Sign-Up"></i>
              </a>
              <a href="logout.php" id="user-logout">
                  <i class="fa-solid fa-arrow-right-from-bracket" data-tooltip="Logout"></i>
              </a>
          </div>
          <img src="images/Narayani.png" alt="logo" class="logo-img">
          <div class="right-inner-sec1-container">
              <a href="#"><i class="fa-solid fa-magnifying-glass" data-tooltip="Search"></i></a>
              <a href="#"><i class="fa-solid fa-bag-shopping" data-tooltip="Cart"></i><span class="cart-value">0</span></a>
          </div>
        </section><hr>
        <section class="quick-links">
            <ul>
                <li>BEST SELLER</li>
                <li>SHOP BY CATAGORY</li>
                <li>JEWELLERY</li>
                <li>EDITOR'S PACK</li>
                <li>BULK ORDER</li>
                <li>HOME</li>
                <li>ABOUT US</li>
                <li>CONTACT US</li>
                <li>SALE</li>
            </ul>
        </section>
          <div class="container">
            <div class="product">
                <div class="product-image">
                    <img src="<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>">
                </div>
                <div class="product-details">
                    <h1><?php echo $row['product_name']; ?></h1>
                    <p><?php echo $row['product_desc']; ?></p><br>
                    <h3><b>Price: ₹ <?php echo $row['product_price']; ?></b><br><span>Inclusive of all taxes.</span><br><span class="ship"><a href="shipping.php"><u>Shipping</u></a> calculated at checkout.</span></h3>
                    <label for="quantity"><b>Quantity:</b> </label>
                    <input type="number" value="1" id="quantity">
                    <h2>Product details</h2>
                    <p><b>Material used :</b> <span class="details"><?php echo $row['product_material']; ?></span></p>
                    <p><b>Length :</b> <span class="details"><?php echo $row['product_L'] . " cm"; ?></span></p>
                    <p><b>Width :</b> <span class="details"><?php echo $row['product_W'] . " cm"; ?></span></p>
                    <p><b>Height :</b> <span class="details"><?php echo $row['product_H'] . " cm"; ?></span></p>
                    <p><b>Weight :</b> <span class="details"><?php echo $row['product_weight'] . " gm"; ?></span></p>
                    <p><b>Capacity :</b> <span class="details"><?php echo $row['product_capacity'] . " liters"; ?></span></p>
                    <p><b>Color :</b> <span class="details"><?php echo $row['product_color']; ?></span></p>
                    <button class="buy-now">Buy Now <i class="fa-solid fa-money-check-dollar"></i></button>
                    <button class="add-to-cart">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
                </div>
            </div>
          </div>
          <h1 class="related-h1">Based on ur taste</h1><hr>
          <section class="special">
              <?php
                $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
                $current_product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $query = mysqli_query($conn, "SELECT product_type FROM products WHERE product_id = $current_product_id");
                $current_product = mysqli_fetch_assoc($query);
                $current_product_type = $current_product['product_type'];
                $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id != $current_product_id AND product_type='$current_product_type' LIMIT 8");
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
                    echo "<div class='card'>";
                    echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
                    echo "<p>" . $row['product_name'] . "</p>";
                    echo "<p>Price: ₹ " . $row['product_price'] . "</p>";
                    echo "</div></a>";
                  }
              ?>
            </section>
    <footer>
          <section class="footer-container">
            <div class="footer-left-container">
              <u><label for="input-wno"><p class="ptag">Get notified first ↓</p></label></u><br>
              <input type="number" class="input" id="input-wno" placeholder="Enter WhatsApp Number">
              <input type="submit" class="button"></input>
            </div>
            <div class="footer-right-container">
              <u><p>Quick Links</p></u>
              <ul>
                <li>About Us</li>
                <li>Delivery Timeline</li>
                <li>Shipping & Returns</li>
                <li>Privacy Policy</li>
                <li>Custom Orders</li>
                <li>Bulk Orders</li>
                <li>Contact Us</li>
              </ul>
            </div>
          </section>
          <hr class="footer-hr">
          <section class="sec-footer-container">
            <div class="sec-left-footer-container">
              <ul>
                <li><i class="fa-brands fa-google-pay"></i></li>
                <li><i class="fa-brands fa-cc-mastercard"></i></li>
                <li><i class="fa-brands fa-cc-visa"></i></li>
              </ul>
            </div>
            <div class="sec-right-footer-container">
              <ul>
                <li><i class="fa-brands fa-facebook"></i></li>
                <li><i class="fa-brands fa-square-instagram"></i></li>
                <li><i class="fa-brands fa-youtube"></i></li>
              </ul>
            </div>
          </section>
          <p class="powered">@2025, Narayani Handlooms Powered By Vyanktesh Computers</p>
        </footer>

</body>
</html>
