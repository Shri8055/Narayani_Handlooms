<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Narayani Handlooms | ADMIN</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="upload.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="back-btn">
        <a href="admin_dash.php"><input type="button" value="Back ↩️" class="back-button"></a>
    </div>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <h2>CREATE NEW PRODUCT</h2><hr>
    <img src="images/Narayani-removebg.png" alt="form-image" class="form-image">
        <div class="top-left-form">
            <div class="inner-left-top-left-form">
                <label class="top-left-form-label" for="name">Product Name: </label>
                <input type="text" id="name" placeholder="Product name" name="product_name" required><br><br>
            </div>
            <div class="inner-right-top-left-form">
                <label class="top-left-form-label" for="category">Product Type: </label>
                <select id="category" name="category_select">
                    <option value="" disabled selected>Select a category</option>
                    <option value="Featured">Featured</option>
                    <option value="Special">Special</option>
                    <option value="Collector">Collector | Jewellery</option>
                    <option value="New arrival">New arrival</option>
                    <option value="Gift Bag">Gift bag</option>
                </select><br><br>
            </div>
            <div class="inner-right-top-left-form">
                <label class="top-left-form-label" for="genre">Product genre: </label>
                <select id="genre" name="genre">
                    <option value="" disabled selected>Select a Genre</option>
                    <option value="Men Bags">Men Bags</option>
                    <option value="Women Bags">Women Bags</option>
                    <option value="Men & Women Bags">Men & Women Bags</option>
                    <option value="Men Jewellery">Men Jewellery</option>
                    <option value="Women Jewellery">Women Jewellery</option>
                    <option value="Women Jewellery">Men & Women Jewellery</option>
                    <option value="Accessories">Accessories</option>
                    <option value="Decor Items">Decor Items</option>
                    <option value="Gift Hampers">Gift Hampers</option>
                </select><br><br>
            </div>
        </div>
        <div class="upload-image">
            <label for="image">Banner image (Size < 50kb): </label>
            <input type="file" id="image" name="product_image">
        </div>
        <div class="upload-multiple-image">
            <label for="image">Multiple image (Max 5): </label>
            <input type="file" id="images" name="product_images[]" multiple>
        </div>
        <div class="desc-textarea">
            <label for="desc">Short Description: </label>
            <textarea name="desc" id="desc" placeholder="Short description" rows="4" cols="86" name="desc"></textarea><br><br>
        </div>
        <div class="material-textarea">
            <label for="material">Material used: </label><br>
            <textarea name="material" id="material" placeholder="Material used" rows="4" cols="86" name="material"></textarea><br><br>
        </div>
        <h2>DIMENTIONS & CAPACITY</h2><hr>
        <div class="outer-layout">
            <div class="dimetions">
                <div class="inner-dimentions">
                    <label for="length">Length (cm): </label>
                    <input type="decimal" id="length" placeholder="Length in cm" name="product_l"><br><br>
                </div>
                <div class="inner-dimentions">
                    <label for="width">Width (cm): </label>
                    <input type="decimal" id="width" placeholder="Width in cm" name="product_w"><br><br>
                </div>
                <div class="inner-dimentions">
                    <label for="height">Height (cm): </label>
                    <input type="decimal" id="height" placeholder="Height in cm" name="product_h"><br><br>
                </div>
            </div>
            <div class="capacity">
                <div class="inner-capacity">
                    <label for="weight">Weight (gm): </label>
                    <input type="decimal" id="weight" placeholder="Weight in gm" name="product_weight"><br><br>
                </div>
                <div class="inner-capacity">
                    <label for="capacity">Capacity (ltr): </label>
                    <input type="number" id="capacity" placeholder="Capacity in ltr" name="product_capacity"><br><br>
                </div>
                <div class="inner-capacity">
                    <label for="color">Color: </label>
                    <input type="text" id="color" placeholder="Product color" name="color"><br><br>
                </div>
            </div>
        </div>
        <div class="type">
            <div class="inner-type">
                <label for="price">Original Price: </label><br>
                <input type="number" id="price" placeholder="Price in ₹" required name="Oriprice"><br><br>
            </div>
            <div class="inner-type">
                <label for="price">Discount Price: </label><br>
                <input type="number" id="price" placeholder="Price in ₹" required name="Disprice"><br><br>
            </div>
        </div>
        <hr>
        <div class="form-btn">
            <input type="submit" value="Add product 👜" class="btn" name="addProduct">
        </div>
    </form>
</body>
</html>
<?php
    $conn=mysqli_connect("localhost", "root", "", "narayani", 4306);
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["addProduct"])){
            $product_name=$_POST["product_name"];
            $product_category=$_POST["category_select"];
            $product_genre=$_POST["genre"];
            $product_desc=$_POST["desc"];
            $product_material=$_POST["material"];
            $product_length=$_POST["product_l"];
            $product_width=$_POST["product_w"];
            $product_height=$_POST["product_h"];
            $product_weight=$_POST["product_weight"];
            $product_capacity=$_POST["product_capacity"];
            $product_color=$_POST["color"];
            $product_Oriprice=$_POST["Oriprice"];
            $product_Disprice=$_POST["Disprice"];
            if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0){
                $image_name = $_FILES['product_image']['name'];
                $image_tmp_name = $_FILES['product_image']['tmp_name'];
                $image_folder = "uploads/" . $image_name;
                if(move_uploaded_file($image_tmp_name, $image_folder)){
                    echo "";
                } else {
                    echo '<script>alert("Failed to upload image!")</script>';
                }
            }else{
                echo '<script>alert("No image upload or error in file upload!")</script>';
                $image_folder = "";
            }
            if($product_name!=""){
                $sql="INSERT INTO products (product_name, product_type, genre, product_desc, product_material, product_L, product_W, product_H, product_weight, product_capacity, product_color, ori_price, product_price, product_image)
                VALUES ('$product_name', '$product_category', '$product_genre','$product_desc', '$product_material', $product_length, $product_width, $product_height, $product_weight, $product_capacity, '$product_color', $product_Oriprice, $product_Disprice, '$image_folder')";
                if(mysqli_query($conn, $sql)){
                    $product_id = mysqli_insert_id($conn);
                    if(!empty($_FILES["product_images"]["tmp_name"][0])){
                        $total_images = count($_FILES["product_images"]["tmp_name"]);
                        for($i=0;$i<$total_images;$i++){
                            $image=file_get_contents($_FILES["product_images"]["tmp_name"][$i]);
                            $image=mysqli_real_escape_string($conn, $image);
                            $sql_gallery="INSERT INTO product_images (product_id, image_data) 
                                            VALUES($product_id, '$image')";
                            mysqli_query($conn, $sql_gallery);
                        }
                    }
                    echo '<script>alert("Product and images added successfully! ✔")</script>';
                    ob_end_flush();
                    exit();
                } else {
                    echo '<script>alert("Error adding product!")</script>';
                }
            }
        }
    }
?>