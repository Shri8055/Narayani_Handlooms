<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h2>Invalid request</h2>";
    exit;
}
if (isset($_GET['id'])) {
  $_SESSION['sel_product_id'] = (int)$_GET['id'];
}
$product_id = (int) $_GET['id'];
$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Product not found</h2>";
    exit;
}
$row = mysqli_fetch_assoc($result);
$image_query = "SELECT image_data FROM product_images WHERE product_id = $product_id";
$image_result = mysqli_query($conn, $image_query);
$images = [];
while ($img_row = mysqli_fetch_assoc($image_result)) {
    $images[] = base64_encode($img_row['image_data']);
}
$image_query = "SELECT image_data FROM product_images WHERE product_id = $product_id LIMIT 1";
$image_result = mysqli_query($conn, $image_query);
$product_image = "";
if ($image_row = mysqli_fetch_assoc($image_result)) {
    $product_image = "data:image/jpeg;base64," . base64_encode($image_row['image_data']);
}
$cart_count = 0;
if (isset($_SESSION['user_id'])) {
    $cart_count_query = "SELECT SUM(quantity) as total FROM cart WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $cart_count_result = mysqli_query($conn, $cart_count_query);

    if ($cart_count_result) {
        $cart_count_row = mysqli_fetch_assoc($cart_count_result);
        $cart_count = $cart_count_row['total'] ?? 0;
    }
}
// buy now
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy_now'])) {
  $_SESSION['sel_product_id'] = $_POST['product_id']; // Fix: Removed extra dot (.)
  header('Location: checkout.php');
  exit;
}

// add to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
      echo "<script>
                alert('Please Sign-in / Log-in to proceed.');
                window.location.href='product.php?id=" . $_SESSION['sel_product_id'] . "';
            </script>";
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $product_id = (int) $_POST['product_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = (float) $_POST['product_price'];
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id <= 0 || empty($product_name) || $product_price <= 0 || empty($product_image)) {
        echo "<script>alert('Invalid product details.');</script>";
        exit;
    }

    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => $quantity
        ];
    }
    $cart_check_query = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    $cart_check_result = mysqli_query($conn, $cart_check_query);

    if (mysqli_num_rows($cart_check_result) > 0) {
        $update_query = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($conn, $update_query);
    } else {
        $insert_query = "INSERT INTO cart (user_id, product_id, product_name, product_price, product_image, quantity) 
                         VALUES ($user_id, $product_id, '$product_name', $product_price, '$product_image', $quantity)";
        mysqli_query($conn, $insert_query);
    }

    header("Location: product.php?id=$product_id&cart_success=1");
    exit();
}
?>

<?php if (isset($_GET['cart_success']) && $_GET['cart_success'] == 1): ?>
<script>
    alert("Item added to cart successfully!");
    window.location.href = "product.php?id=<?php echo $product_id; ?>";
</script>
<?php endif; ?>

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
                      <div class="input-container">
                        <label for="user"><i class="fa-solid fa-envelope"></i></label>
                        <input type="email" id="user" name="email" placeholder="Email" required>
                      </div>

                      <div class="input-container">
                        <label for="pass"><i class="fa-solid fa-key"></i></label>
                        <input type="password" id="pass" name="password" placeholder="Password" required>
                      </div>

                          <a href="#" class="forgot-pass"><p>Forgot password ?</p></a>
                          <button type="submit" name="login" class="login-btn">Login</button>
                      </form>
                  </div>
                  <div id="Signup" class="tabcontent">
                      <form action="home.php" method="POST">
                        <div class="input-container">
                          <label for="fname"><i class="fa-solid fa-user"></i></label>
                          <input type="text" id="fname" name="name" placeholder="Full Name" required>
                        </div>

                        <div class="input-container">
                          <label for="email"><i class="fa-solid fa-envelope"></i></label>
                          <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>

                        <div class="input-container">
                          <label for="pass"><i class="fa-solid fa-key"></i></label>
                          <input type="password" id="pass" name="password" placeholder="Password" required>
                        </div>
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
                <span class="cart-value"><?php echo htmlspecialchars($cart_count); ?></span>
              </a>
          </div>
        </section><hr>
        <section class="quick-links">
            <ul>
                <li>HOME</li>
                <li>SALE / OFFERS</li>
                <li>BEST SELLER</li>
                <li>SHOP BY CATAGORY</li>
                <li>JEWELLERY</li>
                <li>CUSTOMISED ORDER</li>
                <li>BULK ORDER</li>
                <li>ABOUT US</li>
                <li>CONTACT US</li>
            </ul>
        </section>
          <div class="container">
            <div class="product">
                <div class="product-image">
                    <img id="mainImage" src="<?php echo $row['product_image']; ?>" 
                        alt="<?php echo $row['product_name']; ?>">
                </div>
                <div class="additional-images" id="thumbnails">
                    <img src="<?php echo $row['product_image']; ?>" 
                        class="extra-image" 
                        onclick="changeImage(this)">
                    <?php foreach ($images as $image): ?>
                        <img src="data:image/jpeg;base64,<?php echo $image; ?>" 
                            class="extra-image" 
                            onclick="changeImage(this)">
                    <?php endforeach; ?>
                </div> 
                <form class="form" method="POST" action="product.php?id=<?php echo $product_id; ?>">
    <div class="product-details">
        <h1><?php echo $row['product_name'];?></h1>
        <p><?php echo $row['product_desc']; ?></p><br>
        <h3><b>Price: ₹ <?php echo $row['product_price']; ?></b><br>
            <span>Inclusive of all taxes.</span><br>
            <span class="ship"><a href="shipping.php"><u>Shipping</u></a> calculated at checkout.</span>
        </h3>
        
        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>">

        <label for="quantity"><b>Quantity:</b> </label>
        <input type="number" name="quantity" value="1" id="quantity" min="1" required>

        <h2>Product details</h2>
        <p><b>Material used :</b> <span class="details"><?php echo $row['product_material']; ?></span></p>
        <p><b>Length :</b> <span class="details"><?php echo $row['product_L'] . " cm"; ?></span></p>
        <p><b>Width :</b> <span class="details"><?php echo $row['product_W'] . " cm"; ?></span></p>
        <p><b>Height :</b> <span class="details"><?php echo $row['product_H'] . " cm"; ?></span></p>
        <p><b>Weight :</b> <span class="details"><?php echo $row['product_weight'] . " gm"; ?></span></p>
        <p><b>Capacity :</b> <span class="details"><?php echo $row['product_capacity'] . " liters"; ?></span></p>
        <p><b>Color :</b> <span class="details"><?php echo $row['product_color']; ?></span></p>

        <!-- "Buy Now" will redirect directly to checkout.php -->
        <form class="form" method="POST" action="product.php?id=<?php echo $product_id; ?>">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['product_name']); ?>">
            <input type="hidden" name="price" value="<?php echo $row['product_price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($row['product_image']); ?>">
            <input type="hidden" name="quantity" value="1"> 
            <button type="submit" name="buy_now">Buy Now</button>
        </form>
        <!-- "Add to Cart" will remain inside the original form -->
        <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart <i class="fa-solid fa-cart-shopping"></i></button>
    </div>
</form>
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
                $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id != $current_product_id AND product_type='$current_product_type' ORDER BY RAND() LIMIT 8");
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
                    $_SESSION['sel_product_id']=$row['product_id'];
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
        <?php
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
                          echo "User registered successfully!";
                          echo '<script>alert("User registered successfully!")</script>';
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
        <script>
          function changeImage(thumbnail){
              let mainImage = document.getElementById("mainImage");
              let thumbnailsContainer = document.getElementById("thumbnails");
              let oldMainImageSrc = mainImage.src;
              mainImage.src = thumbnail.src;
              let newThumbnail = document.createElement("img");
              newThumbnail.src = oldMainImageSrc;
              newThumbnail.className = "extra-image";
              newThumbnail.width = 50;
              newThumbnail.height = 50;
              newThumbnail.onclick = function () {
                  changeImage(newThumbnail);
              };
              thumbnailsContainer.replaceChild(newThumbnail, thumbnail);
          }
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    
                    let form = this.closest('form');
                    let formData = new FormData(form);

                    fetch('cart.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(() => {
                        let cartValue = document.querySelector('.cart-value');
                        cartValue.textContent = parseInt(cartValue.textContent) + 1;
                    });
                });
            });
          </script>
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
          </script>
          <script src="index.js" defer></script>
</body>
</html>