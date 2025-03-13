<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>
              alert('Please Sign-in / Log-in to proceed.');
              window.location.href='home.php';
          </script>";
    exit;
} else {
    $user_id = $_SESSION['user_id'];
}

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
    ORDER BY o.order_id, oi.product_id;";

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
</head>
<body>
<a class="btn" href="home.php"><button>BACK 🔙</button></a>

<div class="container order-container">
    <h2 class="text-center mb-4">Your Orders</h2>

    <?php 
    $currentOrderId = null; 
    $orderTotal = 0; 

    while ($row = $result->fetch_assoc()) { 
        $imageSrc = htmlspecialchars($row['product_image']);
        $orderDate = date("d M Y, h:i A", strtotime($row['created_at']));

        // Check if a new order starts
        if ($currentOrderId !== $row['order_id']) { 
            if ($currentOrderId !== null) { 
                echo "</div>"; // Close previous order div
            }
            $currentOrderId = $row['order_id']; 
            $orderTotal = 0; // Reset for new order
    ?>
        <!-- Order Box -->
        <div class="order-box">
            <div class="order-header">
                <h4>Order ID: <?php echo $row['order_id']; ?></h4>
                <p class="order-total"><strong>Total Order Amount:</strong> ₹ <?php echo number_format($row['total_amount'], 2); ?></p>

            </div>
            <p><strong>Order Placed:</strong> <?php echo $orderDate; ?></p>
            <hr>
    <?php } ?>

        <!-- Order Items -->
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
        // Update the total
        $orderTotal += $row['subtotal'];
    } 

    // Close the last order div
    if ($currentOrderId !== null) { 
        echo "</div>"; 
    }
    ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
