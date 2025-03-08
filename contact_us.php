<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>
              alert('Please Sign-in / Log-in to proceed.');
              window.location.href='home.php';
          </script>";
      exit;
}else{
    $user_id=$_SESSION['user_id'];
}

$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$prefillRes=mysqli_query($conn, "SELECT * from users WHERE user_id='$user_id'");
$prefill=mysqli_fetch_assoc($prefillRes);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $query = "INSERT INTO contact (full_name, email, msg_txt) VALUES ('$full_name', '$email', '$message')";
    if (mysqli_query($conn, $query)) {
        $result = mysqli_query($conn, "SELECT COUNT(id) AS total FROM contact");
        $row = mysqli_fetch_assoc($result);
        $_SESSION['contact_count'] = $row['total'];
        echo "<script>alert('Message sent successfully!\nYou will receive email reply within 2-3 working days.'); window.location.href='contact_us.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact_us.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <a href="home.php"><button>BACK ↩️</button></a>
    <form action="contact_us.php" method="post">
        <div class="main-container">
            <div class="container">
                <h1>Contact Us</h1><hr><br>
                <label for="fname">Full name: </label>
                <input type="text" id="fname"  name="full_name" value="<?php echo $prefill['user_name']?>" placeholder="Full name" required><br>
                
                <label for="email">E-mail: </label>
                <input type="email" id="email" name="email" value="<?php echo $prefill['user_email']?>" placeholder="E-mail" required><br>

                <label for="msg">Message:</label>
                <textarea name="message" id="msg" rows="5" cols="20" placeholder="Message..." required></textarea><br>

                <input class="btn" type="submit" value="SUBMIT">
                <p>Once Message sent, You will receive email reply within 2-3 working days.</p>
            </div>
        </div>
    </form>
</body>
</html>