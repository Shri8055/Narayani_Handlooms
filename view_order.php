<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = $_GET['order_id'];


    $sql = "SELECT 
                o.order_id, 
                o.user_id, 
                o.order_status, 
                oi.product_id, 
                p.product_name, 
                p.product_image,
                p.product_price,
                oi.quantity AS product_quantity, 
                t.payment_status
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN products p ON oi.product_id = p.product_id
            LEFT JOIN transactions t ON o.order_id = t.order_id
            WHERE o.order_id = $order_id";

    $result = mysqli_query($conn, $sql);
} else {
    die("Invalid order ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Order</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

<a class="btn" href="orders.php"><button>🔙 Back to Orders</button></a>
<h3>Order ID: <?php echo $order_id; ?></h3>
<div class="container">
    <h2 class="text-center">Order Details</h2>
    <?php
        $order_total = 0;
    ?>
    <table class="orders-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { 
                $total = $row['product_quantity'] * $row['product_price'];
                $order_total += $total;
            ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['product_id']; ?></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['product_image']); ?>" alt="Product Image" width="80"></td>
                <?php $total=$row['product_quantity'] * $row['product_price']?>
                <td><?php echo $row['product_quantity']; ?></td>
                <td><?php echo $row['product_price']; ?></td>
                <td><?php echo number_format($total, 2); ?></td>
                <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                <td><?php echo htmlspecialchars($row['payment_status'] ?: 'Pending'); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <h3>ORDER TOTAL: ₹<?php echo number_format($order_total, 2); ?></h3>
</div>

</body>
</html>
