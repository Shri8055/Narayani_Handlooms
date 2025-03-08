<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$product = isset($_GET['view']) ? trim(mysqli_real_escape_string($conn, urldecode($_GET['view']))) : '';

$query = "";

if ($product == 'Gift Hamper | Decor | Accessories') {
    $query = "SELECT * FROM products WHERE genre IN ('Accessories', 'Decor Items', 'Gift Hampers')";
} else {
    $query = "SELECT * FROM products WHERE product_type='$product'";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View <?php echo htmlspecialchars($product); ?></title>
    <link rel="stylesheet" href="view.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <h1><?php echo htmlspecialchars($product); ?> Collection</h1>
    <hr>
    <section class="special">
        <?php
          $result = mysqli_query($conn, $query); 
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<a href='product.php?id=" . $row['product_id'] . "' target='_blank' class='card-link'>";
                  echo "<div class='card'>";
                  echo "<img src='" . $row['product_image'] . "' alt='" . htmlspecialchars($row['product_name']) . "'>";
                  echo "<p>" . htmlspecialchars($row['product_name']) . "</p>";
                  echo "<p>MRP: ₹ " . htmlspecialchars($row['ori_price']) . "</p>";
                  echo "<p>Selling Price: ₹ " . htmlspecialchars($row['product_price']) . "</p>";
                  echo "</div></a>";
              }
          } else {
              echo "<p>No products found in this category.</p>";
          }
          mysqli_close($conn);
        ?>
    </section>
</body>
</html>