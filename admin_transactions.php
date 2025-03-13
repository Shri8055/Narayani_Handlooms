<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle payment status update before closing the connection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];

    $update_query = "UPDATE transactions SET payment_status='$payment_status' WHERE order_id='$order_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_transactions.php"); // Maintain filter
        exit();
    }
}

// Fetch transactions
$query = "SELECT transaction_id, order_id, user_id, user_name, amount, transaction_date, transaction_time, payment_status FROM transactions ORDER BY transaction_date DESC";
$result = mysqli_query($conn, $query);

// Fetch transaction count
$count_query = "SELECT COUNT(transaction_id) AS total FROM transactions";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$_SESSION['transaction_count'] = $count_row['total'];

// **Now close the connection at the end**
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