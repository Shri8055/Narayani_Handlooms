<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_SESSION['user_id'])) {
    echo "<script>
              alert('Please Sign-in / Log-in to proceed.');
              window.location.href='home.php';
          </script>";
    exit;
} else {
    $user_id = $_SESSION['user_id'];
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
$sql = "SELECT 
        o.order_id, o.order_status, t.payment_status, 
        o.created_at, -- Fetch order creation date
        (SELECT SUM(subtotal) FROM order_items WHERE order_items.order_id = o.order_id) AS total_amount, -- Fix order total calculation
        oi.quantity, oi.unit_price, oi.subtotal, 
        p.product_name, p.product_image 
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    LEFT JOIN transactions t ON o.order_id = t.order_id
    WHERE o.user_id = ?
    ORDER BY o.order_id DESC, oi.product_id;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="urorders.css">
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
                <a href="home.php#about-us"><li>ABOUT US</li></a>
                <a href="contact_us.php"><li>CONTACT US</li></a>
            </ul>
        </section>
<a class="btn" href="home.php"><button>BACK 🔙</button></a>

<div class="container order-container">
    <h2 class="text-center mb-4">Your Orders</h2><hr class="cont-hr">
    
    <?php 
    $currentOrderId = null; 
    $orderTotal = 0; 

    while ($row = $result->fetch_assoc()) { 
        $imageSrc = htmlspecialchars($row['product_image']);
        $orderDate = date("d M Y, h:i A", strtotime($row['created_at']));

        if ($currentOrderId !== $row['order_id']) { 
            if ($currentOrderId !== null) { 
                echo "</div>";
            }
            $currentOrderId = $row['order_id']; 
            $orderTotal = 0; 
    ?>
        <div class="order-box">
            <div class="order-header">
                <h4>Order ID: <?php echo $row['order_id']; ?></h4>
                <p class="order-total"><strong>Total Order Amount:</strong> ₹ <?php echo number_format($row['total_amount'], 2); ?></p>
            </div>
            <div class="inner-order-header">
                <p><strong>Order Placed:</strong> <?php echo $orderDate; ?></p>
                <button class="p-btn"><a href="#">🔁 Buy Again</a></button>
            </div>
            <hr class="order-hr">
    <?php } ?>

        <div class="order-item">
            <img src="<?php echo $imageSrc; ?>" alt="Product Image">
            <div class="order-details">
                    <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>
                    <p><strong>Price:</strong> ₹ <?php echo number_format($row['unit_price'], 2); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo $row['payment_status']; ?></p>
                    <p><strong>Order Status:</strong> <?php echo $row['order_status']; ?></p>
            </div>
        </div>

    <?php 
        $orderTotal += $row['subtotal'];
    } 

    if ($currentOrderId !== null) { 
        echo "</div>"; 
    }
    ?>
</div>
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

<?php
$stmt->close();
$conn->close();
?>