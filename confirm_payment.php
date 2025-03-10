<?php
session_start();
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

    <form method="POST" onsubmit="return validateForm(event)">
        <label for="">UPI transaction id:</label>
        <input type="text" id="transaction_id" name="transaction_id" placeholder="Enter UPI Transaction ID" required><br><br>

        <label for="">Date of transaction:</label>
        <input type="date" id="transaction_date" name="transaction_date" required><br><br>
        
        <label for="">Exact Time of transaction:</label>
        <input type="time" id="transaction_time" name="transaction_time" required><br><br>
        <button type="submit">Confirm Payment</button>
    </form>
</div>

<script>
function validateForm(event) {
    event.preventDefault();
    let transactionId = document.getElementById("transaction_id").value.trim();
    let transactionDate = document.getElementById("transaction_date").value;
    let transactionTime = document.getElementById("transaction_time").value;
    if (transactionId === "" || transactionDate === "" || transactionTime === "") {
        alert("Please fill in all the fields before confirming payment.");
        return false;
    }
    setTimeout(function() {
        alert("Your details are sent, we will confirm your order in 1-2 working days.\nYou will receive an email about your order.");
        window.location.href = "home.php";
    }, 1000);
    return true;
}
</script>

</body>
</html>
