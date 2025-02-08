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
    <h1>This is admin page.</h1>
    <div class="back-btn">
        <a href="logout.php"><input type="button" value="Back ↩️" class="back-button"></a>
    </div>
    <form action="upload.php" method="post" enctype="multipart/form-data">
    <h2>CREATE NEW PRODUCT</h2><hr>
    <img src="images/Narayani-removebg.png" alt="form-image" class="form-image">
        <div class="top-left-form">
            <div class="inner-left-top-left-form">
                <label class="top-left-form-label" for="name">Enter Product Name: </label><br>
                <input type="text" id="name" placeholder="Enter name" name="product_name" required><br><br>
            </div>
            <div class="inner-right-top-left-form">
                <label class="top-left-form-label" for="category">Product Type: </label><br>
                <select id="category" name="category_select">
                    <option value="" disabled selected>Select a category</option>
                    <option value="Featured">Featured</option>
                    <option value="Special">Special</option>
                    <option value="Collector">Collector | Jewellery</option>
                    <option value="New arrival">New arrival</option>
                    <option value="Gift Bag">Gift bag</option>
                </select><br><br>
            </div>
        </div>   
        <div class="desc-textarea">
            <label for="desc">Enter Description: </label><br>
            <textarea name="desc" id="desc" placeholder="Enter product description" rows="7" cols="80" name="desc"></textarea><br><br>
        </div>
        <div class="material-textarea">
            <label for="material">Enter Product Material: </label><br>
            <textarea name="material" id="material" placeholder="Enter product material" rows="5" cols="100" name="material"></textarea><br><br>
        </div><hr>
        <div class="dimetions">
            <div class="inner-dimentions">
                <label for="length">Enter Product Length (cm): </label><br>
                <input type="number" id="length" placeholder="Length in cm" name="product_l"><br><br>
            </div>
            <div class="inner-dimentions">
                <label for="width">Enter Product Width (cm): </label><br>
                <input type="number" id="width" placeholder="Width in cm" name="product_w"><br><br>
            </div>
            <div class="inner-dimentions">
                <label for="height">Enter Product Height (cm): </label><br>
                <input type="number" id="height" placeholder="Height in cm" name="product_h"><br><br>
            </div>
        </div>
        <div class="capacity">
            <div class="inner-capacity">
                <label for="weight">Enter Product Weight (mg): </label><br>
                <input type="number" id="weight" placeholder="Weight in kg" name="product_weight"><br><br>
            </div>
            <div class="inner-capacity">
                <label for="capacity">Enter Product Capacity (ltr): </label><br>
                <input type="number" id="capacity" placeholder="Capacity in ltr" name="product_capacity"><br><br>
            </div>
            <div class="inner-capacity">
                <label for="color">Enter Product Color: </label><br>
                <input type="text" id="color" placeholder="Product color" name="color"><br><br>
            </div>
        </div>
        <div class="type">
            <div class="inner-type">
                <label for="price">Enter Product Price: </label><br>
                <input type="number" id="price" placeholder="Price of product ₹" required name="price"><br><br>
            </div>
        </div><hr>
        <div class="image">
            <label for="image">Upload image: </label><br>
            <input type="file" id="image" name="product_image">
        </div><hr>
        <div class="form-btn">
            <input type="submit" value="Add product 👜" class="btn">
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
        $product_name=$_POST["product_name"];
        $product_category=$_POST["category_select"];
        $product_desc=$_POST["desc"];
        $product_material=$_POST["material"];
        $product_length=$_POST["product_l"];
        $product_width=$_POST["product_w"];
        $product_height=$_POST["product_h"];
        $product_weight=$_POST["product_weight"];
        $product_capacity=$_POST["product_capacity"];
        $product_color=$_POST["color"];
        $product_price=$_POST["price"];

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

        $sql="INSERT INTO products (product_name, product_type, product_desc, product_material, product_L, product_W, product_H, product_weight, product_capacity, product_color, product_price, product_image)
         VALUES ('$product_name', '$product_category', '$product_desc', '$product_material', $product_length, $product_width, $product_height, $product_weight, $product_capacity, '$product_color', $product_price, '$image_folder')";

        if(mysqli_query($conn, $sql)){
            echo '<script>alert("Product added successfully! ✔")</script>';
            ob_end_flush();
            exit();
        }else{
            echo '<script>alert("Error adding product!")</script>';
        }
    }
?>