<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (!isset($_SESSION['user_id'])) {
    echo "<script>
              alert('Please Sign-in / Log-in to proceed.');
              window.location.href='home.php';
          </script>";
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    // Fetch the ordered items
    $query = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // Clear previous buy now session data
    $_SESSION['buynow'] = [];
    while ($row = $result->fetch_assoc()) {
        $_SESSION['buynow'][] = [
            'product_id' => $row['product_id'],
            'bn_price' => $row['product_price'],
            'bn_quantity' => $row['quantity']
        ];
        $total_price += $row['product_price'] * $row['quantity'];
    }
    $_SESSION['total_price'] = $total_price;
    $stmt->close();
    $conn->close();
    // Redirect to checkout page
    header("Location: checkout.php?buy_again=true&order_id=" . urlencode($order_id));
    exit;
}
?>