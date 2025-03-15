<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $transaction_id = $_POST['transaction_id'];
    $transaction_date = $_POST['transaction_date'];
    $transaction_time = $_POST['transaction_time'];
    $amount = $_SESSION['total_price'] ?? 0;
    $order_id = $_SESSION['order_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    $user_name = $_SESSION['user_name'] ?? '';

    if (!$order_id || !$user_id) {
        die("Session data missing. Please login again.");
    }

    $check_sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $transaction_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Transaction ID already exists. Use a unique one.'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO transactions (transaction_id, order_id, user_id, user_name, amount, transaction_date, transaction_time, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "siisdss", $transaction_id, $order_id, $user_id, $user_name, $amount, $transaction_date, $transaction_time);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Payment details submitted! We will verify your order soon. You will receive an email about your order.');
                window.location.href='home.php';
            </script>";
        exit();
    } else {
        die("Error inserting data: " . mysqli_stmt_error($stmt));
    }
}
if (isset($_POST['confirm_payment'])) {
    $user_id = $_SESSION['user_id'];

    $delete_query = "DELETE FROM buynow WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_query);

    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Payment</title>
    <link rel="stylesheet" href="confirm_payment.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-container">
    <h1>Thank you for your purchase! 🛍️ We truly appreciate your support and trust in Narayani Handlooms. Your order will be placed successfully, and you can view the details on your order page. Happy shopping! 😊</h1>
</div>
<div class="container">
    <h2>Confirm Your Payment</h2><hr><br>
    <p class="note-n">Please enter valid UPI transaction ID you received after payment.</p><br>

    <form method="post">
        <label for="">UPI transaction id:</label>
        <input type="text" id="transaction_id" name="transaction_id" placeholder="Enter UPI Transaction ID" required><br><br>

        <label for="">Date of transaction:</label>
        <input type="date" id="transaction_date" name="transaction_date" required><br><br>
        
        <label for="">Exact Time of transaction:</label>
        <input type="time" id="transaction_time" name="transaction_time" required><br><br>
        <button type="submit" name="confirm_payment">Confirm Payment</button>
    </form>
</div>
</body>
</html>
