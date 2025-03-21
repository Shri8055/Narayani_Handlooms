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
        $order_id = intval($_POST['order_id']); // Ensure integer type
        $order_status = $_POST['order_status'];
    
        error_log("Updating Order ID: " . $order_id); // Debugging: Check order ID
    
        // Prepare the UPDATE query for product count only if status is "In Transit"
        if ($order_status == "In Transit") {
            $update_products_query = "
                UPDATE products p
                JOIN order_items oi ON p.product_id = oi.product_id
                JOIN orders o ON oi.order_id = o.order_id
                SET p.product_count = p.product_count + oi.quantity
                WHERE o.order_id = ?
            ";
    
            $stmt = mysqli_prepare($conn, $update_products_query);
            mysqli_stmt_bind_param($stmt, "i", $order_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        // Update the order status AFTER updating product count
        $update_order_query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
        $stmt = mysqli_prepare($conn, $update_order_query);
        mysqli_stmt_bind_param($stmt, "si", $order_status, $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        // Redirect to maintain the selected filter
        header("Location: orders.php?order_type=$selected_order_type");
        exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <h1>Total Orders : <?php echo $_SESSION['order_count'] . " , "; ?>  <?php 
                                                                        echo $selected_order_type; 
                                                                        if($selected_order_type=='Pending'){ 
                                                                            echo " : " . $_SESSION['pending_count'];
                                                                        }
                                                                        else if($selected_order_type=='Processing'){
                                                                            echo " : " . $_SESSION['processing_count'];
                                                                        }
                                                                        else if($selected_order_type=='In Transit'){
                                                                            echo " : " . $_SESSION['transit_count'];
                                                                        }
                                                                        else if($selected_order_type=='Delivered'){
                                                                            echo " : " . $_SESSION['delivered_count'];
                                                                        }
                                                                        else if($selected_order_type=='Cancelled'){
                                                                            echo " : " . $_SESSION['cancelled_count'];
                                                                        }
                                                                        else{
                                                                            echo "";
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
                <th>DELETE</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <?php 
                    $full_name = $row['first_name'] . ' ' . $row['last_name'];
                    
                    //$country_code = preg_replace('/[^\d+]/', '', $row['phone_country_code']);
                    //$ph_no = $country_code . ' ' . $row['phone_number'];

                    $ph_no = $row['phone_country_code'] . ' ' . $row['phone_number'];
                    $add = $row['address'] . ' ' . $row['extra_address'];
                ?>
                <tr>
                <td class="td"><a href="view_order.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>">
                    <?php echo htmlspecialchars($row['order_id']); ?>
                </a></td>
                    <td class="td"><?php echo htmlspecialchars($row['user_id']); ?></td>
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
                    <td><?php echo htmlspecialchars($row['total_price']);?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="order_status" onchange="this.form.submit()">
                                <option value="Pending" style="background-color:rgb(243, 241, 142);" <?php if ($row['order_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Processing" style="background-color:rgb(142, 203, 243);" <?php if ($row['order_status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                                <option value="In Transit" style="background-color:rgb(243, 203, 142);" <?php if ($row['order_status'] == 'In Transit') echo 'selected'; ?>>In Transit</option>
                                <option value="Delivered" style="background-color:rgb(171, 243, 142);" <?php if ($row['order_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" style="background-color:rgb(243, 142, 142);" <?php if ($row['order_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="td"><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td class="td-a"><a href="del_order.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>"><i class="fa-solid fa-xmark"></i></a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
