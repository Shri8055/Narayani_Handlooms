<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}
$user_id = $_SESSION['user_id'] ?? 0;
$product_id = $_GET['id'] ?? 0;
$action = $_GET['action'] ?? '';

if ($user_id && $product_id) {
    if ($action === "increase") {
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
    } elseif ($action === "decrease") {
        $query = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE user_id = $user_id AND product_id = $product_id";
    }
    if (isset($query) && mysqli_query($conn, $query)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>