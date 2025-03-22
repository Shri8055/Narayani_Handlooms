<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sendMail($to, $subject, $msg) {
    $mail = new PHPMailer();
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shrinivaskangralkar8055@gmail.com';
        $mail->Password = 'vedt izue arff tcpp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('shrinivaskangralkar8055@gmail.com', 'Narayani Handlooms');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $msg;
        $mail->SMTPOptions = array('ssl'=>array(
            'verify_peer'=>false,
            'verify_peer_name'=>false,
            'allow_self_signed'=>false
        ));
        
        if (!$mail->Send()) {
            return $mail->ErrorInfo;  // Return the error message
        } else {
            return true;  // Return boolean true on success
        }
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST['transaction_id'];
    $transaction_date = $_POST['transaction_date'];
    $transaction_time = $_POST['transaction_time'];
    $amount = $_SESSION['total_price'] ?? 0;
    $order_id = $_SESSION['order_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    $user_email = $_SESSION['user_email'] ?? '';
    $user_name = $_SESSION['username'] ?? '';

    if (!$order_id || !$user_id) {
        die("Session data missing. Please login again.");
    }

    // Check if transaction ID already exists
    $check_sql = "SELECT * FROM transactions WHERE transaction_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $transaction_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Transaction ID already exists. Use a unique one.'); window.history.back();</script>";
        exit();
    }

    // Insert transaction data
    $sql = "INSERT INTO transactions (transaction_id, order_id, user_id, user_name, amount, transaction_date, transaction_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "siisdss", $transaction_id, $order_id, $user_id, $user_name, $amount, $transaction_date, $transaction_time);

    $cart_items = $_SESSION['cart_items'] ?? [];
    $cart_note = "Order: " . count($cart_items) . " items\n";
    foreach ($cart_items as $item) {
        $cart_note .= $item['name'] . " (Qty: " . $item['quantity'] . "), ";
    }
    if (mysqli_stmt_execute($stmt)) {
        $delete_query = "DELETE FROM buynow WHERE user_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $user_id);
        mysqli_stmt_execute($delete_stmt);
        
        // Send Email
        $email_body = "<h2>Thank you for your payment, $user_name!</h2>
                        <p>We are currently processing your order. Once confirmed, we will send you a confirmation email.</p>
                        <p><strong>Order ID:</strong> $order_id</p>
                        <p><strong>Transaction ID:</strong> $transaction_id</p>
                        <p><strong>Order Deatails:</strong> $cart_note</p>
                        <p><strong>Amount Paid:</strong> ₹$amount</p>
                        <p><strong>Transaction Date:</strong> $transaction_date</p>
                        <p><strong>Transaction Time:</strong> $transaction_time</p>
                        <p>We appreciate your support! If you have any questions, feel free to <a href='http://localhost/NarayaniHandlooms/contact_us.php'>Contact Us</a>.</p><br>
                        <p>To see your payment status and order status <a href='http://localhost/NarayaniHandlooms/your_orders.php'>Click Here</a></p>";

        $mail_sent = sendMail($user_email, 'Payment Confirmation', $email_body);
        
        if ($mail_sent === true) {
            echo "<script>
                alert('Payment confirmed! You will receive an email about your order.\\n\\nIf mail not visible in INBOX, please check in SPAM.');
                setTimeout(function() {
                    window.location.href = 'home.php';
                }, 100);
            </script>";
        } else {
            echo "Email Error: " . $mail_sent;
        }
        exit();
    } else {
        die("Error inserting data: " . mysqli_stmt_error($stmt));
    }
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

    <form method="post" id="payment-form">
        <label for="transaction_id">UPI transaction id:</label>
        <input type="text" id="transaction_id" name="transaction_id" placeholder="Enter UPI Transaction ID" required><br><br>

        <label for="transaction_date">Date of transaction:</label>
        <input type="date" id="transaction_date" name="transaction_date" required><br><br>
        
        <label for="transaction_time">Exact Time of transaction:</label>
        <input type="time" id="transaction_time" name="transaction_time" required><br><br>
        
        <button type="submit">Confirm Payment</button>
        <!-- <p>Mail will be sent to you</p> -->
        <p class="note-n">After clicking " Confirm Payment " please wait for pop-up.</p><br>
    </form>
</div>
</body>
</html>
