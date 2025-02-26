<?php
session_start();
$conn = new mysqli("localhost", "root", "", "your_database_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate product ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM products WHERE id = $id");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Encode image as base64 from database
        $base64Image = 'data:image/jpeg;base64,' . base64_encode($product['image']);

        // Add product to session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += 1; // Increase quantity if exists
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $base64Image,
                'quantity' => 1
            ];
        }
    }
}

$conn->close();
header("Location: cart_page.php");
exit();
