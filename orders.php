<?php
    session_start();
    $conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get selected order type
    $selected_order_type = isset($_GET['order_type']) ? $_GET['order_type'] : 'All';

    // Construct query based on order type
    $query = "SELECT order_id, user_id, followup, country, first_name, last_name, address, extra_address, company, state, city, pin_code, phone_country_code, phone_number, shipping_instructions, total_price, order_status, created_at FROM orders";

    if ($selected_order_type != 'All') {
        $query .= " WHERE order_status = '$selected_order_type'";
    }

    $query .= " ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    // Count orders for different statuses
    function getOrderCount($conn, $status) {
        $sql = "SELECT COUNT(order_id) AS total FROM orders WHERE order_status = '$status'";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($res);
        return $row['total'] ?? 0;
    }

    $_SESSION['order_count'] = getOrderCount($conn, 'Pending') + getOrderCount($conn, 'Processing') + getOrderCount($conn, 'In Transit') + getOrderCount($conn, 'Delivered') + getOrderCount($conn, 'Cancelled');
    $_SESSION['pending_count'] = getOrderCount($conn, 'Pending');
    $_SESSION['processing_count'] = getOrderCount($conn, 'Processing');
    $_SESSION['transit_count'] = getOrderCount($conn, 'In Transit');
    $_SESSION['delivered_count'] = getOrderCount($conn, 'Delivered');
    $_SESSION['cancelled_count'] = getOrderCount($conn, 'Cancelled');

    // Update order status
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['order_status'])) {
        $order_id = $_POST['order_id'];
        $order_status = $_POST['order_status'];

        $update_query = "UPDATE orders SET order_status='$order_status' WHERE order_id='$order_id'";
        if (mysqli_query($conn, $update_query)) {
            header("Location: orders.php?order_type=$selected_order_type"); // Maintain filter
            exit();
        } else {
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN | Orders</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <a href="admin_dash.php"><button>BACK ↩️</button></a>
    <a href="orders.php"><button>View All</button></a>
    <a href="admin_transactions.php"><button>TRANSACTIONS ➡</button></a>
    
    <div class="card-container">
        <div class="card pending"><a class="atag colp" href="orders.php?order_type=Pending"><p class="pend">Pending : <?php echo $_SESSION['pending_count'] ?></p></a></div>
        <div class="card processing"><a class="atag colpr" href="orders.php?order_type=Processing"><p class="proc">Processing : <?php echo $_SESSION['processing_count']?></p></a></div>
        <div class="card in-transit"><a class="atag colt" href="orders.php?order_type=In Transit"><p class="tran">In Transit : <?php echo $_SESSION['transit_count']?></p></a></div>
        <div class="card delivered"><a class="atag cold" href="orders.php?order_type=Delivered"><p class="deli">Delivered : <?php echo $_SESSION['delivered_count']?></p></a></div>
        <div class="card cancelled"><a class="atag colc" href="orders.php?order_type=Cancelled"><p class="canc">Cancelled : <?php echo $_SESSION['cancelled_count']?></p></a></div>
    </div>

    <div class="container">
        <h1>Orders (Total: <?php echo $_SESSION['order_count']; ?>) - <?php 
                                                                        echo $selected_order_type; 
                                                                        if($selected_order_type=='Pending'){
                                                                            echo " (Total: " . $_SESSION['pending_count'] . ")";
                                                                        }
                                                                        else if($selected_order_type=='Processing'){
                                                                            echo " (Total: " . $_SESSION['processing_count'] . ")";
                                                                        }
                                                                        else if($selected_order_type=='In Transit'){
                                                                            echo " (Total: " . $_SESSION['transit_count'] . ")";
                                                                        }
                                                                        else if($selected_order_type=='Delivered'){
                                                                            echo " (Total: " . $_SESSION['delivered_count'] . ")";
                                                                        }
                                                                        else{
                                                                            echo " (Total: " . $_SESSION['cancelled_count'] . ")";
                                                                        }
                                                                      ?></h1><hr>
        <table>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>E-mail / Ph.no</th>
                <th>Country</th>
                <th>Name</th>
                <th>Company</th>
                <th>Address</th> 
                <th>State</th>
                <th>City</th>
                <th>Pin-Code</th>
                <th>Ph. No</th>
                <th>Shipping Instructions</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>Date & Time</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php 
                    $full_name = $row['first_name'] . ' ' . $row['last_name'];
                    $ph_no = $row['phone_country_code'] . ' ' . $row['phone_number'];
                    $add = $row['address'] . ' ' . $row['extra_address'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['followup']); ?></td>
                    <td><?php echo htmlspecialchars($row['country']); ?></td>
                    <td><?php echo htmlspecialchars($full_name); ?></td>
                    <td><?php echo htmlspecialchars($row['company']); ?></td>
                    <td><?php echo htmlspecialchars($add); ?></td>
                    <td><?php echo htmlspecialchars($row['state']); ?></td>
                    <td><?php echo htmlspecialchars($row['city']); ?></td>
                    <td><?php echo htmlspecialchars($row['pin_code']); ?></td>
                    <td><?php echo htmlspecialchars($ph_no); ?></td>
                    <td><?php echo htmlspecialchars($row['shipping_instructions']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="order_status" onchange="this.form.submit()">
                                <option value="Pending" <?php if ($row['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" <?php if ($row['order_status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="In Transit" <?php if ($row['order_status'] == 'In Transit') echo 'selected'; ?>>In Transit</option>
                                <option value="Delivered" <?php if ($row['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($row['order_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
