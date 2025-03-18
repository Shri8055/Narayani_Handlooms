<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>
              alert('Please Sign-in / Log-in to proceed.');
              window.location.href='home.php';
          </script>";
      exit;
}else{
    $user_id=$_SESSION['user_id'];
}

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$prefillRes=mysqli_query($conn, "SELECT * from users WHERE user_id='$user_id'");
$prefill=mysqli_fetch_assoc($prefillRes);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $query = "INSERT INTO contact (full_name, email, msg_txt) VALUES ('$full_name', '$email', '$message')";
    if (mysqli_query($conn, $query)) {
        $result = mysqli_query($conn, "SELECT COUNT(id) AS total FROM contact");
        $row = mysqli_fetch_assoc($result);
        $_SESSION['contact_count'] = $row['total'];
        echo "<script>
            alert('Message sent successfully!\\nYou will receive an email reply within 2-3 working days.');
            window.location.href='contact_us.php';
            </script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
mysqli_close($conn);
?>
<?php
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact_us.css">
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
        <div class="back-btn"><a href="home.php"><button>BACK ↩️</button></a></div>
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
                <a href="home.php#about-us"><li>ABOUT US</li></a>
                <a href="contact_us.php"><li>CONTACT US</li></a>
            </ul>
        </section>
    <form action="contact_us.php" method="post">
    <div class="form">
        <div class="main-container">
            <div class="container">
                <h1>Contact Us</h1><hr><br>
                <label for="fname">Full name: </label>
                <input type="text" id="fname"  name="full_name" value="<?php echo $prefill['user_name']?>" placeholder="Full name" required><br>
                
                <label for="email">E-mail: </label>
                <input type="email" id="email" name="email" value="<?php echo $prefill['user_email']?>" placeholder="E-mail" required><br>

                <label for="msg">Message:</label>
                <textarea name="message" id="msg" rows="5" cols="20" placeholder="Message..." required></textarea><br>

                <input class="btn" type="submit" value="SUBMIT">
                <p>Once Message sent, You will receive email reply within 2-3 working days.</p>
            </div>
        </div>
        </div>
    </form>
    <script>
        document.getElementById('user-icon').onclick = function() {
        document.getElementById('popup-container').style.display = 'block';
        }

        document.querySelector('.close').onclick = function() {
        document.getElementById('popup-container').style.display = 'none';
        }

        function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
        }
        // dropdown
        document.addEventListener("DOMContentLoaded", function () {
        const categoryToggle = document.getElementById("category-toggle");

        categoryToggle.addEventListener("click", function () {
            this.classList.toggle("active");
        });
        });
        </script>
    <script src="index.js" defer></script>
</body>
</html>