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
                <span class="close">&times;</span>
                <div class="tabs">
                    <button class="tablinks active" onclick="openTab(event, 'Login')">Login</button>
                    <button class="tablinks" onclick="openTab(event, 'Signup')">Signup</button>
                </div>
                <div id="Login" class="tabcontent active">
                    <h2>Login</h2>
                    <form action="home.php" method="POST">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="login">Login</button>
                    </form>
                </div>
                <div id="Signup" class="tabcontent">
                    <h2>Signup</h2>
                    <form action="home.php" method="POST">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="signup">Signup</button>
                    </form>
                </div>
            </div>
        </div>
        <section class="sec1-container">
          <div class="left-inner-sec1-container">
              <a href="#" id="user-icon">
                  <i class="fa-solid fa-user" data-tooltip="Profile"></i>
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
        </section>
        <hr>
        <section class="quick-links">
            <ul>
                <li>BEST SELLER</li>
                <li>SHOP BY CATAGORY</li>
                <li>JEWELLERY</li>
                <li>EDITOR'S PACK</li>
                <li>HOME</li>
                <li>ABOUT US</li>
                <li>CONTACT US</li>
                <li>SALE</li>
            </ul>
        </section>
        <section class="slide-show">
            <div class="slideshow-container">
                <div class="mySlides fade">
                  <div class="numbertext">1 / 3</div>
                  <img src="images/1.jpg">
                  <div class="text">
                    <p class="slide-header">BEST SELLER</p>
                    <p class="slide-des">EXPLORE OUR BEST SELLER !</p>
                    <button>SHOP NOW</button>
                  </div>
                </div>
                
                <div class="mySlides fade">
                  <div class="numbertext">2 / 3</div>
                  <img src="images/2.jpg" style="width:100%">
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
                
                </div>
                <br>
                
                <div style="text-align:center">
                  <span class="dot" onclick="currentSlide(1)"></span> 
                  <span class="dot" onclick="currentSlide(2)"></span> 
                  <span class="dot" onclick="currentSlide(3)"></span> 
                </div>
        </section><hr class="slide-hr">
        <h4 class="collection-h4">FEATURED COLLECTION</h4>
        <section class="collection">
          <div class="card">
            <img src="images/bag1.jpg" alt="Handloom Bag 1">
            <p class="bag-name">Narayani Handloom 1</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag1.jpg" alt="Handloom Bag 2">
            <p class="bag-name">Narayani Handloom 2</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag1.jpg" alt="Handloom Bag 3">
            <p class="bag-name">Narayani Handloom 3</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag1.jpg" alt="Handloom Bag 4">
            <p class="bag-name">Narayani Handloom 4</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag3.jpg" alt="Handloom Bag 1">
            <p class="bag-name">Narayani Handloom 1</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag3.jpg" alt="Handloom Bag 2">
            <p class="bag-name">Narayani Handloom 2</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag3.jpg" alt="Handloom Bag 3">
            <p class="bag-name">Narayani Handloom 3</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag3.jpg" alt="Handloom Bag 4">
            <p class="bag-name">Narayani Handloom 4</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag4.jpg" alt="Handloom Bag 1">
            <p class="bag-name">Narayani Handloom 1</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag4.jpg" alt="Handloom Bag 2">
            <p class="bag-name">Narayani Handloom 2</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag4.jpg" alt="Handloom Bag 3">
            <p class="bag-name">Narayani Handloom 3</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag4.jpg" alt="Handloom Bag 4">
            <p class="bag-name">Narayani Handloom 4</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
        </section>
        <button class="view-all-cards">VIEW ALL </button><hr>
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
          <div class="card">
            <img src="images/bag2.jpg" alt="Handloom Bag 1">
            <p class="bag-name">Narayani Handloom 1</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag2.jpg" alt="Handloom Bag 2">
            <p class="bag-name">Narayani Handloom 2</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag2.jpg" alt="Handloom Bag 3">
            <p class="bag-name">Narayani Handloom 3</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bag2.jpg" alt="Handloom Bag 4">
            <p class="bag-name">Narayani Handloom 4</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
        </section>
        <button class="view-all-cards">VIEW ALL </button><hr>
        <h4 class="other-collection-h4">COLLECTOR ITEMS</h4>
        <section class="other-collection">
          <div class="card">
            <img src="images/bracelate1.jpg" alt="Handloom Bag 1">
            <p class="bag-name">Narayani Handloom 1</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bracelate1.jpg" alt="Handloom Bag 2">
            <p class="bag-name">Narayani Handloom 2</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bracelate1.jpg" alt="Handloom Bag 3">
            <p class="bag-name">Narayani Handloom 3</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
          <div class="card">
            <img src="images/bracelate1.jpg" alt="Handloom Bag 4">
            <p class="bag-name">Narayani Handloom 4</p>
            <p class="ori-price">₹<del>5000/-</del> <span class="dis-price">₹4999/-</span></p>
          </div>
        </section>
        <button class="view-all-cards">VIEW ALL </button><hr>
        <section class="about-us">
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
          $stmt->bind_result($hashed_password, $username);
          $stmt->fetch();
          if(password_verify($user_pass, $hashed_password)){
            $_SESSION['username'] = $username;
            if($user_email==="Narayani2025@gmail.com"){
              echo "<script>alert('Admin Login Successful'); window.location.href='upload.php';</script>";
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