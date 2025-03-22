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

// Handle payment status update before closing the connection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];

    $update_query = "UPDATE transactions SET payment_status='$payment_status' WHERE order_id='$order_id'";
    
    if (mysqli_query($conn, $update_query)) {
        if ($payment_status == 'Completed' || $payment_status == 'Failed') {
            // Fetch required details for email
            $query = "SELECT 
            u.user_name, 
            u.user_email AS user_email,
            oi.order_id, 
            SUM(oi.quantity * oi.unit_price) AS total_amount, 
            t.transaction_id, 
            t.transaction_date, 
            t.transaction_time
          FROM transactions t
          JOIN orders o ON t.order_id = o.order_id
          JOIN users u ON o.user_id = u.user_id
          JOIN order_items oi ON o.order_id = oi.order_id
          WHERE t.order_id = '$order_id'
          GROUP BY oi.order_id, t.transaction_id, t.transaction_date, t.transaction_time, u.user_name, u.user_email ORDER BY t.transaction_id DESC, t.transaction_time DESC";

$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    // Assign fetched values to variables
    $user_name = $row['user_name'];
    $user_email = $row['user_email'];
    $order_id = $row['order_id'];
    $total_amount = $row['total_amount']; // Now correctly calculated
    $transaction_id = $row['transaction_id'];
    $transaction_date = $row['transaction_date'];
    $transaction_time = $row['transaction_time'];

    // Prepare email body based on payment status
    if ($payment_status == 'Completed') {
        $subject = 'Payment Received';
        $email_body = "<h2>Your Payment Has Been Confirmed, $user_name!</h2>
                       <p>We are pleased to inform you that your payment has been successfully verified by our team.</p>
                       <p>Your order is now being processed and will be dispatched soon.</p>
                       <p><strong>Order ID:</strong> $order_id</p>
                       <p><strong>Transaction ID:</strong> $transaction_id</p>
                       <p><strong>Total Amount Paid:</strong> ₹$total_amount</p>
                       <p><strong>Transaction Date:</strong> $transaction_date</p>
                       <p><strong>Transaction Time:</strong> $transaction_time</p>
                       <p><strong>Payment Status:</strong> <span style='color: green; font-weight: bold;'>Confirmed ✅</span></p>
                       <p>We appreciate your trust in us! Your order will be shipped soon, and you will receive another email with tracking details.</p>
                       <p>If you have any questions, feel free to <a href='http://localhost/NarayaniHandlooms/contact_us.php'>Contact Us</a>.</p><br>
                       <p>To check your updated order status, <a href='http://localhost/NarayaniHandlooms/your_orders.php'>Click Here</a>.</p>";
    } else { // Payment Failed
        $subject = 'Payment Failed';
        $email_body = "<h2>Hello, $user_name!</h2>
                       <p>We regret to inform you that your payment could not be processed due to a failed transaction or incorrect payment details.</p>
                       <p>Unfortunately, your order has not been placed.</p>
                       <p><strong>Order ID:</strong> $order_id</p>
                       <p><strong>Transaction ID:</strong> $transaction_id</p>
                       <p><strong>Total Amount Attempted:</strong> ₹$total_amount</p>
                       <p><strong>Transaction Date:</strong> $transaction_date</p>
                       <p><strong>Transaction Time:</strong> $transaction_time</p>
                       <p><strong>Payment Status:</strong> <span style='color: red; font-weight: bold;'>Failed ❌</span></p>
                       <p>Please ensure that your payment details are correct and try again.</p>
                       <p>To place your order again, go to <a href='http://localhost/NarayaniHandlooms/your_orders.php'>Your Orders</a> and click on 'Buy Again'.</p>
                       <p>If you need any assistance, feel free to <a href='http://localhost/NarayaniHandlooms/contact_us.php'>Contact Us</a>.</p><br>
                       <p>We apologize for the inconvenience and appreciate your patience.</p>";
    }

    // Send email
    $mail_sent = sendMail($user_email, $subject, $email_body);

    if ($mail_sent === true) {
        echo "<script>
                alert('Payment status updated! Mail sent to $user_name.');
                setTimeout(function() {
                    window.location.href = 'admin_transactions.php';
                }, 100);
            </script>";
    } else {
        echo "Email Error: " . $mail_sent;
    }
    exit();
}
        } else {
            echo "<script>
                    window.location.href='admin_transactions.php';
                  </script>";
        }
        exit();
    }
}

// Fetch transactions
$query = "SELECT 
            t.transaction_id, 
            t.order_id, 
            o.user_id, 
            u.user_name, 
            (oi.quantity * oi.unit_price) AS amount, 
            t.transaction_date, 
            t.transaction_time, 
            t.payment_status
        FROM transactions t
        JOIN orders o ON t.order_id = o.order_id
        JOIN users u ON o.user_id = u.user_id
        JOIN order_items oi ON o.order_id = oi.order_id
        ORDER BY t.transaction_date DESC";

$result = mysqli_query($conn, $query);

// Fetch transaction count
$count_query = "SELECT COUNT(transaction_id) AS total FROM transactions";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$_SESSION['transaction_count'] = $count_row['total'];

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN | Transactions</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <a href="orders.php"><button>BACK ↩️</button></a>
    <div class="container">
    <h1>Transactions : <?php echo $_SESSION['transaction_count']; ?></h1><hr>
    <table>
        <tr>
            <th>Transaction ID</th>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Transaction Date</th>
            <th>Transaction Time</th> 
            <th>Payment Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td class="ttd"><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['order_id']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['user_id']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['amount']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['transaction_date']); ?></td>
                <td class="ttd"><?php echo htmlspecialchars($row['transaction_time']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select id="payment" name="payment_status" onchange="this.form.submit()">
                            <option value="Pending" <?php if ($row['payment_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Completed" <?php if ($row['payment_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Failed" <?php if ($row['payment_status'] == 'Failed') echo 'selected'; ?>>Failed</option>
                        </select>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table></div>
</body>
</html>