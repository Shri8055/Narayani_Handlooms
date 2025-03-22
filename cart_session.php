<?php
// this file is made to render which items to show buy now or cart
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
$conn=mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$total_price=0;
$cart_items=[];
var_dump($_GET);
if (isset($_GET['buy_again']) || $_SESSION['buy_again'] == 'true' && isset($_GET['order_id'])) {
    $order_id = (int) $_GET['order_id']; // Ensure order_id is an integer
    // Fetch last order details
    $query = "SELECT oi.product_id, p.product_name, p.product_image, oi.unit_price, oi.quantity 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.product_id 
              WHERE oi.order_id = ?"; // No need to join orders table
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo "Buy Again block in cart session";
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = [
            'name' => $row['product_name'],
            'quantity' => $row['quantity']
        ];
        $total_price += $row['unit_price'] * $row['quantity'];
    }
    $stmt->close();
}
else if (isset($_SESSION['buy_now']) && !empty($_SESSION['buy_now'])) {
    $user_id = $_SESSION['user_id'];
    $buynow_query = "SELECT * FROM buynow WHERE user_id = $user_id LIMIT 1";
    $buynow_result = mysqli_query($conn, $buynow_query);
    echo "Buy now block in cart session";
    if ($buynow_row = mysqli_fetch_assoc($buynow_result)) {
        $cart_items[] = [
            'name' => $buynow_row['product_name'],
            'quantity' => $buynow_row['quantity']
        ];
        $total_price += $buynow_row['product_price'] * $buynow_row['quantity'];
    }
}else{
    $user_id = $_SESSION['user_id'];
    $cart_query = "SELECT * FROM cart WHERE user_id = $user_id";
    $cart_result = mysqli_query($conn, $cart_query);
    echo "Cart block in cart session";
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