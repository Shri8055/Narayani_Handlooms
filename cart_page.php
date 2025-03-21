<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("<h2>Please log in to view your cart.</h2>");
}
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$user_id = $_SESSION['user_id'];
$cart_items = [];
$total_price = 0;
$db_cart_query = "SELECT * FROM cart WHERE user_id = $user_id";
$db_cart_result = mysqli_query($conn, $db_cart_query);
if ($db_cart_result) {
    while ($db_cart_item = mysqli_fetch_assoc($db_cart_result)) {
        $cart_items[$db_cart_item['product_id']] = [
            'name' => $db_cart_item['product_name'],
            'price' => $db_cart_item['product_price'],
            'image' => $db_cart_item['product_image'],
            'quantity' => $db_cart_item['quantity']
        ];
        $total_price += $db_cart_item['product_price'] * $db_cart_item['quantity'];
    }
}
if (isset($_POST['checkout_cart'])) {
    $_SESSION['checkout_mode'] = 'cart';
    header("Location: checkout.php");
    exit;
}
unset($_SESSION['buy_now']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="cart_page.css">
    <script>
        function updateQuantity(productId, action) {
            fetch(`update_cart.php?id=${productId}&action=${action}`, { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Error updating quantity!");
                    }
                })
                .catch(error => console.error("Error:", error));
        }
    </script>
</head>
<body>
<div class="container">
    <div class="cart-header">
        <h1>Your Cart</h1>
        <span class="cart-total"><b>Total: ₹<?= number_format($total_price, 2) ?></b></span>
    </div>
    <div class="conti-shop">
        <a href="home.php"><span class="conti-shop-span">Continue shopping</span></a>
    </div>
    <?php if (empty($cart_items)) : ?>
        <h2 style="text-align:center; color:#555;">Your cart is empty.</h2>
    <?php else : ?>
    <table>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Remove</th>
        </tr>
        <?php foreach ($cart_items as $id => $product) : ?>
        <tr>
            <td><img src="<?php echo $product['image']; ?>" width="50"></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>₹<?= number_format($product['price'], 2) ?></td>
            <td>
                <button class="btn btn-decrease" onclick="updateQuantity(<?= $id ?>, 'decrease')">-</button>
                <?= $product['quantity'] ?>
                <button class="btn btn-increase" onclick="updateQuantity(<?= $id ?>, 'increase')">+</button>
            </td>
            <td><a href="remove_from_cart.php?id=<?= $id ?>" class="btn-remove">Remove</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <span class="cart-total-f">Total: ₹<?= number_format($total_price, 2) ?></span>
    <a href="checkout.php" class="checkout-btn" name="checkout_cart">Proceed to Checkout</a>
    <?php endif; ?>
</div>
</body>
</html>