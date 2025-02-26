<?php
  session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Narayani Handlooms</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="shipping.css">
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
        </section><hr><br>
        
        <div class="container">
            <h1>Shipping Policy</h1><br>
            <h4><u>Shipping Information</u></h4><br>
            <p>Availability : We shipped Pan India.</p><br>
            <p>Processing time : Allow 7-10 business days processing time for your order to be shipped.</p><br>
            <h4><u>PLEASE NOTE: </u></h4>
            <ul>It takes 7-10 business days for deliveries pan India, that's excluding our processing time. We hope for your kind patience and understanding in this matter.</ul>
            <ul>All packages are shipped via Delhivery and Bluedart courier service.</ul><br>
            <p>Once your order is shipped, we will notify you via Email or SMS.</p><br><br>
            <p>To keep track on your order, please whatsapp us at +91 1234567890 or write to us at Narayani2025@gmail.com.</p><br><br>
            <p>Thank you !</p><br>
        </div>
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
        $stmt=$conn->prepare("SELECT user_pass, user_name FROM users WHERE user_email=?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
          $stmt->bind_result($hashed_password, $user_name);
          $stmt->fetch();
          if(password_verify($user_pass, $hashed_password)){
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