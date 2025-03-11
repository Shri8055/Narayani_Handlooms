<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$query = "SELECT full_name, email, msg_txt, submitted_at FROM contact ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);

$count_query = "SELECT COUNT(id) AS total FROM contact";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$_SESSION['contact_count'] = $count_row['total'];

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN | Contact</title>
    <link rel="stylesheet" href="admin_contact.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <a href="admin_dash.php"><button>BACK ↩️</button></a>
    <h1>Contact Messages (Total: <?php echo $_SESSION['contact_count']; ?>)</h1><hr>
    <table>
        <tr>
            <th>Name</th>
            <th>E-mail</th>
            <th>Message</th>
            <th>Date & Time<br>YYYY-MM-DD</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td class="td1"><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td class="td2"><?php echo htmlspecialchars($row['email']); ?></td>
                <td class="td3"><?php echo htmlspecialchars($row['msg_txt']); ?></td>
                <td class="td4"><?php echo htmlspecialchars($row['submitted_at']); ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
