<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
$conn = mysqli_connect('localhost', 'root', '', 'narayani', 4306);
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $product_category = $_POST['category_select'];
    $product_genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $product_desc = $_POST['desc'];
    $product_material = $_POST['material'];
    $product_length = $_POST['product_l'];
    $product_width = $_POST['product_w'];
    $product_height = $_POST['product_h'];
    $product_weight = $_POST['product_weight'];
    $product_capacity = $_POST['product_capacity'];
    $product_color = $_POST['colors'];
    $ori_price = $_POST['ori_price'];
    $dis_price = $_POST['dis_price'];
    if (!empty($_FILES['product_image']['name'])) {
        $image_folder = 'uploads/' . basename($_FILES['product_image']['name']);
        move_uploaded_file($_FILES['product_image']['tmp_name'], $image_folder);
    } else {
        $image_folder = $row['product_image'];
    }

    if (!isset($product_id) || empty($product_id)) {
        echo "<h2>Invalid Product ID</h2>";
        exit;
    }
    $update_query = "UPDATE products SET 
        product_name = '$product_name', 
        product_type = '$product_category', 
        product_desc = '$product_desc',
        genre = '$product_genre',
        product_material = '$product_material', 
        product_L = '$product_length', 
        product_W = '$product_width', 
        product_H = '$product_height', 
        product_weight = '$product_weight', 
        product_capacity = '$product_capacity', 
        product_color = '$product_color', 
        ori_price = '$ori_price', 
        product_price = '$dis_price', 
        product_image = '$image_folder' 
        WHERE product_id = '$product_id'";

    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dash.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
    <title>Narayani Handlooms | ADMIN</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="admin_edit.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="back-btn">
    <a href="admin_dash.php" class="back-button">
        <input type="button" value="Back ↩️">
    </a>
    </div>
    <form method="post" enctype="multipart/form-data">
    <h2>EDIT/DELETE PRODUCT</h2><hr>
    <img src="images/Narayani-removebg.png" alt="form-image" class="form-image">
        <div class="top-left-form">
            <div class="inner-left-top-left-form">
                <label class="top-left-form-label" for="name">Product Name: </label>
                <input type="text" id="name" placeholder="Product name" name="product_name" required value="<?php echo $row['product_name']?>"><br><br>
            </div>
            <div class="inner-right-top-left-form">
                <label class="top-left-form-label" for="category">Product Type: </label>
                <select id="category" name="category_select">
                    <option value="New arrival" <?php echo ($row['product_type'] == 'New arrival') ? 'selected' : ''; ?>>New arrival</option>
                    <option value="Gift Bag"  <?php echo ($row['product_type'] == 'Gift bag') ? 'selected' : ''; ?>>Gift bag</option>
                    <option value="Featured" <?php echo ($row['product_type'] == 'Featured') ? 'selected' : ''; ?>>Featured</option>
                    <option value="Special" <?php echo ($row['product_type'] == 'Special') ? 'selected' : ''; ?>>Special</option>
                    <option value="Collector" <?php echo ($row['product_type'] == 'Collector') ? 'selected' : ''; ?>>Collector</option>
                    <option value="Decor" <?php echo ($row['product_type'] == 'Decor') ? 'selected' : ''; ?>>Decor</option>
                    <option value="Accesories" <?php echo ($row['product_type'] == 'Accesories') ? 'selected' : ''; ?>>Accesories</option>
                    <option value="Gift Hamper" <?php echo ($row['product_type'] == 'Gift Hamper') ? 'selected' : ''; ?>>Gift Hamper</option>
                </select><br><br>
            </div>
            <div class="inner-right-top-left-form">
                <label class="top-left-form-label" for="genre">Product Genre: </label>
                <select id="genre" name="genre">
                    <option value="Men bags" <?php echo ($row['genre'] == 'Men bags') ? 'selected' : ''; ?>>Men bags</option>
                    <option value="Women bags"  <?php echo ($row['genre'] == 'Women bags') ? 'selected' : ''; ?>>Women bags</option>
                    <option value="Men & Women bags" <?php echo ($row['genre'] == 'Men & Women bags') ? 'selected' : ''; ?>>Men & Women bags</option>
                    <option value="Accesories" <?php echo ($row['genre'] == 'Accesories') ? 'selected' : ''; ?>>Accesories</option>
                    <option value="Men Jewellery" <?php echo ($row['genre'] == 'Men Jewellery') ? 'selected' : ''; ?>>Men Jewellery</option>
                    <option value="Women Jewellery" <?php echo ($row['genre'] == 'Women Jewellery') ? 'selected' : ''; ?>>Women Jewellery</option>
                    <option value="Men & Women Jewellery" <?php echo ($row['genre'] == 'Men & Women Jewellery') ? 'selected' : ''; ?>>Men & Women Jewellery</option>
                    <option value="Decor Items" <?php echo ($row['genre'] == 'Decor Items') ? 'selected' : ''; ?>>Decor Items</option>
                    <option value="Gift Hampers" <?php echo ($row['genre'] == 'Gift Hampers') ? 'selected' : ''; ?>>Gift Hampers</option>
                </select><br><br>
            </div>
        </div>
        <div class="upload-image">
            <label for="image">Banner image (Size < 50kb): </label>
            <input type="file" id="image" name="product_image"><br>
            <img src="<?php echo $row['product_image']; ?>" width="200"><br>
        </div>
        <div class="upload-multiple-image">
            <label for="image">Multiple image (Max 5): </label>
            <input type="file" id="images" name="product_images[]" multiple><br>
            <?php foreach ($images as $image): ?>
                <div class="inner-images">
                    <img src="data:image/jpeg;base64,<?php echo $image; ?>" class="extra-image" onclick="changeImage(this)"><br>
                    <button>DELETE</button>
                </div>
            <?php endforeach; ?>
        </div><br>
        <div class="desc-textarea">
            <label for="desc">Short Description: </label><br>
            <textarea name="desc" id="desc" placeholder="Short description" rows="4" cols="86" name="desc"><?php echo htmlspecialchars($row['product_desc']); ?></textarea><br><br>
        </div>
        <div class="material-textarea">
            <label for="material">Material used: </label><br>
            <textarea name="material" id="material" placeholder="Material used" rows="4" cols="86" name="material"><?php echo htmlspecialchars($row['product_material']); ?></textarea><br><br>
        </div>
        <h2>DIMENTIONS & CAPACITY</h2><hr>
        <div class="outer-layout">
            <div class="dimetions">
                <div class="inner-dimentions">
                    <label for="length">Length (cm): </label>
                    <input type="decimal" id="length" placeholder="Length in cm" name="product_l" value="<?php echo $row['product_L']?>"><br><br>
                </div>
                <div class="inner-dimentions">
                    <label for="width">Width (cm): </label>
                    <input type="decimal" id="width" placeholder="Width in cm" name="product_w" value="<?php echo $row['product_W']?>"><br><br>
                </div>
                <div class="inner-dimentions">
                    <label for="height">Height (cm): </label>
                    <input type="decimal" id="height" placeholder="Height in cm" name="product_h" value="<?php echo $row['product_H']?>"><br><br>
                </div>
            </div>
            <div class="capacity">
                <div class="inner-capacity">
                    <label for="weight">Weight (gm): </label>
                    <input type="decimal" id="weight" placeholder="Weight in gm" name="product_weight" value="<?php echo $row['product_weight']?>"><br><br>
                </div>
                <div class="inner-capacity">
                    <label for="capacity">Capacity (ltr): </label>
                    <input type="number" id="capacity" placeholder="Capacity in ltr" name="product_capacity" value="<?php echo $row['product_capacity']?>"><br><br>
                </div>
                <div class="inner-capacity">
                    <label for="color">Color: </label>
                    <input type="text" id="color" placeholder="Product color" name="colors" value="<?php echo $row['product_color']?>"><br><br>
                </div>
            </div>
        </div>
        <div class="type">
            <div class="inner-type">
                <label for="price">Price in ₹: </label><br>
                <input type="number" id="price" placeholder="Price in ₹" required name="ori_price" value="<?php echo $row['ori_price']?>"><br><br>
            </div>
            <div class="inner-type">
                <label for="price">Price in ₹: </label><br>
                <input type="number" id="price" placeholder="Price in ₹" required name="dis_price" value="<?php echo $row['product_price']?>"><br><br>
            </div>
        </div>
        <hr>
        <div class="form-btn">
            <input type="submit" value="Save 💾" class="btn" name="addProduct">
        </div>
    </form>
</body>
</html>