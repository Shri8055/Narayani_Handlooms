<?php
  session_start();
?>
<?php
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<h2>Product not found</h2>";
        exit;
    }
}else{
    echo "<h2>Invalid request</h2>";
    exit;
}
if (isset($_POST['deleteProduct'])) {
    if (!isset($product_id) || empty($product_id)) {
        echo "<h2>Invalid Product ID</h2>";
        exit();
    }

    $delete_query = "DELETE FROM products WHERE product_id = '$product_id'";
    $delete_images_query = "DELETE FROM product_images WHERE product_id = '$product_id'";

    mysqli_query($conn, $delete_images_query);

    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'admin_dash.php';</script>";
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = $product_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "<h2>Product not found</h2>";
        exit;
    }
} else {
    echo "<h2>Invalid request</h2>";
    exit;
}
$image_query = "SELECT image_data FROM product_images WHERE product_id = $product_id";
$image_result = mysqli_query($conn, $image_query);
$images = [];

while ($img_row = mysqli_fetch_assoc($image_result)) {
    $images[] = base64_encode($img_row['image_data']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $row['product_name']; ?> - Narayani Handlooms</title>
    <link rel="stylesheet" href="admin_products.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="container">
    <div class="product">
        <div class="product-image">
            <img id="mainImage" src="<?php echo $row['product_image']; ?>" 
                 alt="<?php echo $row['product_name']; ?>">
        </div>
        <div class="additional-images" id="thumbnails">
            <img src="<?php echo $row['product_image']; ?>" 
                 class="extra-image" 
                 onclick="changeImage(this)">
                 
            <?php foreach ($images as $image): ?>
                <img src="data:image/jpeg;base64,<?php echo $image; ?>" 
                     class="extra-image" 
                     onclick="changeImage(this)">
            <?php endforeach; ?>
        </div> 
        <div class="product-details">
            <h1><?php echo $row['product_name']; ?></h1>
            <p><?php echo $row['product_desc']; ?></p><br>
            <p><b>Product type :</b> <span class="details"><?php echo $row['product_type']; ?></span></p><br>
            <h3><b>Price: ₹ <?php echo $row['product_price']; ?></b><br>
                <span>Inclusive of all taxes.</span><br>
                <span class="ship"><a href="shipping.php"><u>Shipping</u></a> calculated at checkout.</span>
            </h3>
            <label for="quantity"><b>Quantity:</b> </label>
            <input type="number" value="1" id="quantity">
            <h2>Product details</h2>
            <p><b>Material used :</b> <span class="details"><?php echo $row['product_material']; ?></span></p>
            <p><b>Length :</b> <span class="details"><?php echo $row['product_L'] . " cm"; ?></span></p>
            <p><b>Width :</b> <span class="details"><?php echo $row['product_W'] . " cm"; ?></span></p>
            <p><b>Height :</b> <span class="details"><?php echo $row['product_H'] . " cm"; ?></span></p>
            <p><b>Weight :</b> <span class="details"><?php echo $row['product_weight'] . " gm"; ?></span></p>
            <p><b>Capacity :</b> <span class="details"><?php echo $row['product_capacity'] . " liters"; ?></span></p>
            <p><b>Color :</b> <span class="details"><?php echo $row['product_color']; ?></span></p>
            <p><b>Created At :</b> <span class="details"><?php echo $row['created_at']; ?></span></p>
            <p><b>Total Sell :</b> <span class="details"><?php echo $row['product_count']; ?></span></p>
            <a href="admin_edit.php?id=<?php echo $row['product_id']; ?>"><button class="buy-now">EDIT 📝</button></a>
            <form method="POST" action="">
                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                <button type="submit" class="add-to-cart" name="deleteProduct" 
                        onclick="return confirm('Are you sure you want to delete this product?');">
                    DELETE ❌
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function changeImage(thumbnail) {
    let mainImage = document.getElementById("mainImage");
    let thumbnailsContainer = document.getElementById("thumbnails");
    let oldMainImageSrc = mainImage.src;
    mainImage.src = thumbnail.src;
    let newThumbnail = document.createElement("img");
    newThumbnail.src = oldMainImageSrc;
    newThumbnail.className = "extra-image";
    newThumbnail.width = 50;
    newThumbnail.height = 50;
    newThumbnail.onclick = function () {
        changeImage(newThumbnail);
    };
    thumbnailsContainer.replaceChild(newThumbnail, thumbnail);
}
</script>
</body>
</html>