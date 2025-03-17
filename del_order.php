<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    $sql = "DELETE FROM orders WHERE order_id=$order_id";

    $result = mysqli_query($conn, $sql);
    header('Location: orders.php');
} else {
    die("Invalid order ID.");
}
?>