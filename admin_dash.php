<?php
    session_start();
    $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (!isset($_SESSION['admin'])) {
        header("Location: home.php");
        exit();
    }
    $order_count = "SELECT COUNT(order_id) AS ototal FROM orders";
    $Ores = mysqli_query($conn, $order_count);
    $Orow = mysqli_fetch_assoc($Ores);

    $contact_count = "SELECT COUNT(id) AS ctotal FROM contact";
    $Cres = mysqli_query($conn, $contact_count);
    $Crow = mysqli_fetch_assoc($Cres);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_dash.css">
    <title>ADMIN DASHBOARD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="logo">
        <img src="images/Narayani-removebg.png" alt="logo">
    </div><hr class="logo-hr">
    <div class="nav">
        <ul>
            <li><a href="upload.php">Add product</a></li>
            <li>Add offers</li>
            <li><a href="orders.php">Orders<span class="cart-value"><?php echo $Orow['ototal'] ?? '0'; ?></span></a></li>
            <li>Refunds<span class="cart-value">0</span></li>
            <li><a href="admin_contact.php">Contact<span class="cart-value"><?php echo $Crow['ctotal'] ?? '0'; ?></span></a></li>
            <ol>
                <li>
                    <div class="logout-a">
                        <a href="logout.php" id="user-logout" data-tooltip="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </a>
                    </div>
                </li>
            </ol>
        </ul>
    </div><hr>
    <h1>Featured Collection</h1>
    <section class="special">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Featured'");
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='card' onclick=\"window.open('admin_products.php?id=" . $row['product_id'] . "', '_blank')\">";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP : ₹ " . $row['ori_price'] . "</p>";
              echo "<p>SP : ₹ " . $row['product_price'] . "</p><hr>";
              echo "<button>EDIT 📝</button>";
              echo "<button>DELETE ❌</button>";
              echo "</div></a>";
          }
        ?>
    </section><hr>

    <h1>Special Collection</h1>
    <section class="special">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Special'");
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='card' onclick=\"window.open('admin_products.php?id=" . $row['product_id'] . "', '_blank')\">";
              
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP : ₹ " . $row['ori_price'] . "</p>";
              echo "<p>SP : ₹ " . $row['product_price'] . "</p><hr>";
              echo "<button>EDIT 📝</button>";
              echo "<button>DELETE ❌</button>";
              echo "</div></a>";
          }
        ?>
    </section><hr>

    <h1>Collector | Jewellery</h1>
    <section class="special">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE product_type='Collector'");
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='card' onclick=\"window.open('admin_products.php?id=" . $row['product_id'] . "', '_blank')\">";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP : ₹ " . $row['ori_price'] . "</p>";
              echo "<p>SP : ₹ " . $row['product_price'] . "</p><hr>";
              echo "<button>EDIT 📝</button>";
              echo "<button>DELETE ❌</button>";
              echo "</div></a>";
          }
        ?>
    </section><hr>
    <h1>Gift Hampers | Decor | Accessories</h1>
    <section class="special">
        <?php
          $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
          $result = mysqli_query($conn, "SELECT * FROM products WHERE genre='Accessories' OR genre='Decor Items' OR genre ='Gift Hampers'");
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<div class='card' onclick=\"window.open('admin_products.php?id=" . $row['product_id'] . "', '_blank')\">";
              echo "<img src='" . $row['product_image'] . "' alt='" . $row['product_name'] . "'>";
              echo "<p>" . $row['product_name'] . "</p>";
              echo "<p>MRP : ₹ " . $row['ori_price'] . "</p>";
              echo "<p>SP : ₹ " . $row['product_price'] . "</p><hr>";
              echo "<button>EDIT 📝</button>";
              echo "<button>DELETE ❌</button>";
              echo "</div></a>";
          }
        ?>
    </section><hr>
</body>
</html>