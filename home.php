<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_count_query = "SELECT SUM(quantity) AS total FROM cart WHERE user_id = $user_id";
    $cart_count_result = mysqli_query($conn, $cart_count_query);

    if ($cart_count_result) {
        $cart_count_row = mysqli_fetch_assoc($cart_count_result);
        $cart_count = $cart_count_row['total'] ?? 0;
    }
} elseif (isset($_SESSION['cart'])) {
    // Count the total items in session cart for guest users
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['wa_sub'])){
  $user_id=$_SESSION['user_id'];
  $whatsAppNo=mysqli_real_escape_string($conn, $_POST['wa_ph_no']);
  $update_query = "UPDATE users SET user_ph_no='$whatsAppNo' WHERE user_id='$user_id'";
  if(mysqli_query($conn, $update_query)) {
    echo "<script>
            alert('WhatsApp Number Sent!\\nWill notify about new products and offers');
            window.location.href = 'home.php';
          </script>";
      exit();
  }else{
    echo "Error updating record: " . mysqli_error($conn);
  }
}
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // Check if user_id already exists
  $check_query = "SELECT * FROM buynow WHERE user_id = $user_id";
  $result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($result) == 0) {
      // Insert user_id only (no product data yet)
      $insert_query = "INSERT INTO buynow (user_id) VALUES ('$user_id')";
      mysqli_query($conn, $insert_query);
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Narayani Handlooms</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="index.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <header>
        <?php if (isset($_SESSION['username'])): ?>
          <h2 class="banner">
              Hello, <?php echo $_SESSION['username']; ?>
          </h2>
          <?php else: ?>
              <h2 class="banner"></h2>
          <?php endif; ?>
            <h3>WELCOME TO NARAYANI HANDLOOMS 🙏</h3>
        </header>
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
              <a href="cart_page.php">
                <i class="fa-solid fa-bag-shopping" data-tooltip="Cart"></i>
                <span class="cart-value"><?php echo $cart_count; ?></span>
              </a>
          </div>
        </section><hr>
        <section class="quick-links">
            <ul>
                <a href="home.php"><li>HOME</li></a>
                <a href="category.php?category=Sale"><li>SALE / OFFERS</li></a>
                <a href="category.php?category=Best Seller"><li>BEST SELLER</li></a>
                <li id="category-toggle">SHOP BY CATEGORY ▼
                    <ul class="dropdown">
                      <a href="category.php?category=Bags"><li>BAGS</li></a><hr>
                      <a href="category.php?category=Men"><li>MEN</li></a><hr>
                      <a href="category.php?category=Women"><li>WOMEN</li></a><hr>
                      <a href="category.php?category=MenandWomen"><li>MEN & WOMEN</li></a><hr>
                      <a href="category.php?category=Accessories"><li>ACCESSORIES</li></a><hr>
                      <a href="category.php?category=Jewellery"><li>JEWELLERY</li></a><hr>
                      <a href="category.php?category=Decor"><li>DECOR ITEMS</li></a><hr>
                      <a href="category.php?category=Gift hampers"><li>GIFT HAMPERS</li></a>
                    </ul>
                </li>
                <a href="category.php?category=Jewellery"><li>JEWELLERY</li></a>
                <a href="#"><li>CUSTOMISED ORDER</li></a>
                <a href="your_orders.php"><li>YOUR ORDERS</li></a>
                <a href="#about-us"><li>ABOUT US</li></a>
                <a href="contact_us.php"><li>CONTACT US</li></a>
            </ul>
        </section>
        <section class="slide-show">
            <div class="slideshow-container">
                <div class="mySlides fade">
                  <div class="numbertext">1 / 3</div>
                  <img src="images/5.jpg">
                  <div class="text">
                    <p class="slide-header">BEST SELLER</p>
                    <p class="slide-des">EXPLORE OUR BEST SELLER !</p>
                    <button>SHOP NOW</button>
                  </div>
                </div>
                <div class="mySlides fade">
                  <div class="numbertext">2 / 3</div>
                  <img src="images/6.jpeg" style="width:100%">
                  <div class="text">
                    <p class="slide-header">NEW ARRIVAL</p>
                    <p class="slide-des">NEW LAUNCHES BAG NAME !</p>
                    <button>SHOP NOW</button>
                  </div>
                </div>
                <div class="mySlides fade">
                  <div class="numbertext">3 / 3</div>
                  <img src="images/4.jpg" style="width:100%">
                  <div class="text">
                    <p class="slide-header">CUSTOMIZE ORDERS</p>
                    <p class="slide-des">Customize orders in various ways !</p>
                    <button>EXPLORE NOW</button>
                  </div>
                </div>
                <a class="prev" onclick="plusSlides(-1)">❮</a>
                <a class="next" onclick="plusSlides(1)">❯</a>
                </div><br>
                <div style="text-align:center">
                  <span class="dot" onclick="currentSlide(1)"></span> 
                  <span class="dot" onclick="currentSlide(2)"></span> 
                  <span class="dot" onclick="currentSlide(3)"></span> 
                </div>
        </section><hr class="slide-hr">
        <h4 class="collection-h4">FEATURED COLLECTION</h4>
        <section class="collection">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Featured' LIMIT 8");
          while ($row = mysqli_fetch_assoc($result)){
              echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
              echo "<div class='card'>";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP: ₹ " . $row['ori_price'] . "</p>";
              echo "<p>Selling Price: ₹ " . $row['product_price'] . "</p>";
              echo "</div></a>";
          }
        ?>
        </section>
        <a href="view.php?view=Featured"><button class="view-all-cards">VIEW ALL </button></a><hr>
        <section class="gifting">
          <div class="gifting-card">
            <div class="gifting-text">
              <h4>NARAYANI GIFTING</h4>
              <p>Welcome to Narayani India, your go-to destination for unique, handlooms. Our collection features exquisite pieces made by skilled artisans who pour their passion and creativity into every item. Whether you're looking for a special gift for a loved one or a treat for yourself, you'll find a range of beautiful, one-of-a-kind products in our shop.</p>
            </div>
            <div class="gifting-img">
              <img src="images/gift-bag.PNG" alt="">
            </div>
          </div>
        </section><hr>
        <h4 class="special-h4">SPECIAL HANDLOOMS COLLECTION !</h4>
        <section class="special">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Special' LIMIT 4");
          while ($row = mysqli_fetch_assoc($result)){
              echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
              $_SESSION['product_id']=$row['product_id'];
              echo "<div class='card'>";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP: ₹ " . $row['ori_price'] . "</p>";
              echo "<p>Selling Price: ₹ " . $row['product_price'] . "</p>";
              echo "</div></a>";
          }
        ?>
        </section>
        <a href="view.php?view=Special"><button class="view-all-cards">VIEW ALL </button></a><hr>
        <h4 class="other-collection-h4">COLLECTOR ITEMS</h4>
        <section class="other-collection">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Collector' LIMIT 4");
          while ($row = mysqli_fetch_assoc($result)){
              echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
              echo "<div class='card'>";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP: ₹ " . $row['ori_price'] . "</p>";
              echo "<p>Selling Price: ₹ " . $row['product_price'] . "</p>";
              echo "</div></a>";
          }
        ?>
        </section>
        <a href="view.php?view=Collector"><button class="view-all-cards">VIEW ALL </button></a><hr>
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $dispQuery = mysqli_query($conn, "SELECT * FROM products WHERE genre='Accessories' OR genre='Decor Items' OR genre ='Gift Hampers'");
          if (mysqli_num_rows($dispQuery) > 0) { 
          ?>
              <h4 class="other-collection-h4">Gift Hampers | Decor | Accessories</h4>
              <section class="other-collection">
              <?php
                  $result = mysqli_query($conn, "SELECT * FROM products WHERE genre='Accessories' OR genre='Decor Items' OR genre ='Gift Hampers' LIMIT 4");
                  while ($row = mysqli_fetch_assoc($result)){
                      echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
                      echo "<div class='card'>";
                      echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
                      echo "<p>" . $row['product_name'] . "</p>";
                      echo "<p>MRP: ₹ " . $row['ori_price'] . "</p>";
                      echo "<p>Selling Price: ₹ " . $row['product_price'] . "</p>";
                      echo "</div></a>";
                  }
              ?>
              </section>
              <a href="view.php?view=Gift Hamper | Decor | Accessories"><button class="view-all-cards">VIEW ALL</button></a><hr>
          <?php
          } 
        ?>
        <section class="about-us" id="about-us">
          <div class="about-us-container">
            <h4>ABOUT US</h4>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolore ullam atque quidem dolores omnis iusto obcaecati, maiores ratione nostrum. Repellat modi nihil labore voluptatem, dolore sunt ducimus beatae praesentium doloremque?</p><br>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni, sint ab rem quaerat, in dolorum repellat, quisquam veritatis enim mollitia molestias id harum quas expedita modi cumque reiciendis quod facilis.</p>
          </div>
        </section>
        <footer>
          <section class="footer-container">
            <div class="footer-left-container">
              <u><label for="input-wno"><p class="ptag">Get notified first ↓</p></label></u><br>
              <form method="POST" id="form">
                <input type="number" name="wa_ph_no" class="input" id="input-wno" placeholder="WhatsApp Number">
                <input type="submit" name="wa_sub" class="button"></input>
                <p><i>*WRITE COUNTRY CODE</i></p>
              </form>
            </div>
            <div class="footer-right-container">
              <u><p>Quick Links</p></u>
              <ul>
                <li>Shipping & Returns</li>
                <a href="your_orders.php"><li>Your Orders</li></a>
                <li>Custom Orders</li>
                <a href="contact_us.php"><li>Contact Us</li></a>
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
        <script src="index.js" defer></script>
    </body>
</html>
<?php
    $conn=mysqli_connect('localhost', 'root', '', 'narayani', 4306);
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      if(isset($_POST["signup"])){  
        $user_name=$_POST["name"];
        $user_email=$_POST["email"];
        $user_pass=$_POST["password"];
        $hashed_password=password_hash($user_pass, PASSWORD_BCRYPT);
        $check_stmt=$conn->prepare("SELECT user_email FROM users WHERE user_email = ?");
        $check_stmt->bind_param("s", $user_email);
        $check_stmt->execute();
        $check_stmt->store_result();
        if($check_stmt->num_rows>0){
            echo '<script>alert("Email already registered! \n\nPLEASE LOGIN")</script>';
        }else{
            $stmt=$conn->prepare("INSERT INTO users (user_name, user_email, user_pass) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_name, $user_email, $hashed_password);
    
            if($stmt->execute()){
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username']=$user_name;
                echo "<script>alert('User registered successfully!'); window.location.href='home.php';</script>";
                header("Location: home.php?success=1");
                exit();
            }else{
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
      }
      if(isset($_POST["login"])){
        $user_email=$_POST["email"];
        $user_pass=$_POST["password"];
        $stmt=$conn->prepare("SELECT user_id, user_pass, user_name FROM users WHERE user_email=?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
          $stmt->bind_result($user_id, $hashed_password, $user_name);
          $stmt->fetch();
          if(password_verify($user_pass, $hashed_password)){
            $_SESSION['user_id']=$user_id;
            $_SESSION['username']=$user_name;
            if($user_email==="Narayani2025@gmail.com"){
              $_SESSION['admin']=$user_email;
              echo "<script>alert('Admin Login Successful'); window.open('admin_dash.php', '_blank');</script>";
            }
            echo "<script>alert('Login Successful'); window.location.href='home.php';</script>";
          }else{
            echo "<script>alert('Invalid Password');</script>";
          }
        }else{
          echo "<script>alert('Email not found');</script>";
        }
        $stmt->close();
        }
      }
?>