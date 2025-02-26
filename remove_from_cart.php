<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_SESSION['user_id'])) {
    die("Please log in first.");
}
$user_id = $_SESSION['user_id'];
if (isset($_GET['id'])) {
    $product_id = (int) $_GET['id'];
    $query = "DELETE FROM cart WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($conn, $query);
}
header("Location: cart_page.php");
exit();
?>