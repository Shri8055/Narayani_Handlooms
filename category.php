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
// Check if category is set
if (!isset($_GET['category']) || empty($_GET['category'])) {
    echo "<h2>Invalid category</h2>";
    exit;
}

$category = trim(mysqli_real_escape_string($conn, urldecode($_GET['category'])));
if ($category === 'Bags') {
    $query = "SELECT * FROM products WHERE genre IN ('Men Bags', 'Women Bags', 'Men & Women Bags') ORDER BY RAND()";
} else if ($category==='Men') {
    $query = "SELECT * FROM products WHERE genre IN ('Men Bags', 'Men Jewellery') ORDER BY RAND()";
} else if ($category==='Women') {
    $query = "SELECT * FROM products WHERE genre IN ('Women Bags', 'Women Jewellery') ORDER BY RAND()";
} else if ($category==='Decor') {
    $query = "SELECT * FROM products WHERE genre IN ('Decor Items') ORDER BY RAND()";
} else if ($category==='Gift hampers') {
    $query = "SELECT * FROM products WHERE genre IN ('Gift Hampers') ORDER BY RAND()";
} else if ($category==='Jewellery') {
    $query = "SELECT * FROM products WHERE genre IN ('Men Jewellery', 'Women Jewellery', 'Men & Women Jewellery') ORDER BY RAND()";
} else if ($category==='Accessories') {
    $query = "SELECT * FROM products WHERE genre IN ('Accessories') ORDER BY RAND()";
} else if($category==='MenandWomen') {
    $query = "SELECT * FROM products WHERE genre IN ('Men Bags', 'Women Bags', 'Men Jewellery', 'Women Jewellery', 'Men & Women Jewellery', 'Men & Women Bags') ORDER BY RAND()";
} else if($category==='Best Seller') {
    $query = "SELECT * FROM products WHERE product_count>=3 ORDER BY RAND()";
} else {
    $query = "SELECT * FROM products WHERE genre = '$category' ORDER BY RAND()";
}
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($category); ?> - Narayani</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="category.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
                <a href="home.php?"><li>HOME</li></a>
                <a href="category.php?category=Sale"><li>SALE / OFFERS</li></a>
                <a href="category.php?category=Best Seller"><li>BEST SELLER</li></a>
                <li id="category-toggle">SHOP BY CATEGORY ▼
                    <ul class="dropdown">
                      <a href="category.php?category=Bags"><li>BAGS</li></a><hr class="hr-li">
                      <a href="category.php?category=Men"><li>MEN</li></a><hr class="hr-li">
                      <a href="category.php?category=Women"><li>WOMEN</li></a><hr class="hr-li">
                      <a href="category.php?category=MenandWomen"><li>MEN & WOMEN</li></a><hr class="hr-li">
                      <a href="category.php?category=Accessories"><li>ACCESSORIES</li></a><hr class="hr-li">
                      <a href="category.php?category=Jewellery"><li>JEWELLERY</li></a><hr class="hr-li">
                      <a href="category.php?category=Decor"><li>DECOR ITEMS</li></a><hr class="hr-li">
                      <a href="category.php?category=Gift hampers"><li>GIFT HAMPERS</li></a>
                    </ul>
                </li>
                <a href="category.php?category=Jewellery"><li>JEWELLERY</li></a>
                <a href="#"><li>CUSTOMISED ORDER</li></a>
                <a href="#"><li>YOUR ORDERS</li></a>
                <a href="home.php#about-us"><li>ABOUT US</li></a>
                <a href="contact_us.php"><li>CONTACT US</li></a>
            </ul>
        </section>
        <h4 class="collection-h4"><?php echo strtoupper($category); ?> COLLECTION</h4>
<div class="container">
    <section class="collection">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <a href="product.php?id=<?php echo $row['product_id']; ?>" target="_blank" class="card-link">
                    <div class="card">
                        <img src="<?php echo $row['product_image']; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" >
                        <p><?php echo htmlspecialchars($row['product_name']); ?></p>
                        <p>MRP: ₹ <?php echo number_format($row['ori_price'], 2); ?></p>
                        <p>Selling Price: ₹ <?php echo number_format($row['product_price'], 2); ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <h2 style="text-align: center; color: red;">No items available in this category ! <br>Or will be added soon 🔜<br>📢Stay tuned 🤗✨</h2>
        <?php endif; ?>
    </section>
</div>
<script src="index.js" defer></script>
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