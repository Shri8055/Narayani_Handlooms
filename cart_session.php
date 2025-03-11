<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$total_price = 0;
$cart_items = [];

if (isset($_SESSION['buy_now']) && !empty($_SESSION['buy_now'])) {
    foreach ($_SESSION['buy_now'] as $item) {
        $cart_items[] = [
            'name' => $item['bn_name'],
            'quantity' => $item['bn_quantity']
        ];
        $total_price += $item['bn_price'] * $item['bn_quantity'];
    }
    unset($_SESSION['buy_now']); 
} else {
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT * FROM cart WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);
    
    while ($cart_row = mysqli_fetch_assoc($cart_result)) {
        $cart_items[] = [
            'name' => $cart_row['product_name'],
            'quantity' => $cart_row['quantity']
        ];
        $total_price += $cart_row['product_price'] * $cart_row['quantity'];
    }
}

$_SESSION['total_price'] = $total_price;
$_SESSION['cart_items'] = $cart_items;

?>
