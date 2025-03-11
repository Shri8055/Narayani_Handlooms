<?php
session_start();
include 'cart_session.php';
$cart_total = $_SESSION['total_price'];
$cart_items = $_SESSION['cart_items'] ?? [];

$cart_note = "Order: " . count($cart_items) . " items\n";
foreach ($cart_items as $item) {
    $cart_note .= $item['name'] . " (Qty: " . $item['quantity'] . "), ";
}
$cart_note = rtrim($cart_note, ", ");

$upi_id = "shrinivaskangralkar8055@oksbi";
$shop_name = "Narayani Handlooms";

$upi_link = "upi://pay?pa=$upi_id&pn=$shop_name&am=$cart_total&tn=$cart_note&cu=INR";

$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($upi_link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Payment</title>
    <link rel="stylesheet" href="qr_payment.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="main-img">
    <img src="images/payment_logo.png" alt="">
</div>
<div class="container">
    <h2>Scan & Pay</h2>
    <p class="amount">Amount: ₹<?php echo $cart_total; ?></p>
    <p class="note"><?php echo $cart_note; ?></p>

    <img src="<?php echo $qr_code_url; ?>" alt="Payment QR Code" width="300" height="300">
    <p>UPI ID : <?php echo $upi_id; ?></p><br>
    <p class="note">Scan this QR code with any UPI app (<b>Google Pay, PhonePe, Paytm</b>) to complete the payment.</p>
    <br>
    <a href="<?php echo $upi_link; ?>" onclick="redirectAfterPayment()">
        <button>Confirm Payment ➡</button>
    </a>
    <p class="note-r">Once payment done then, click on " Confirm Payment " button, if not then your order will not be placed but your money will be debited.</p>
</div>
<script>
function redirectAfterPayment() {
    setTimeout(function() {
        window.location.href = "confirm_payment.php";
    });
}
</script>
</body>
</html>
