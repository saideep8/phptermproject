<?php
session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

if (!isset($_SESSION['register_as']) || $_SESSION['register_as'] !== 'Seller') {
    echo "<script>alert('Please login as a seller')</script>";
    echo "<script>window.open('../view/logout.php', '_self')</script>";
    exit();
}

if (isset($_POST['add_product'])) {

    $product_name = getFormValue(INPUT_POST, 'product_name', FILTER_DEFAULT);
    $description = getFormValue(INPUT_POST, 'description', FILTER_DEFAULT);
    $keywords = getFormValue(INPUT_POST, 'keywords', FILTER_DEFAULT);
    $category = getFormValue(INPUT_POST, 'category', FILTER_DEFAULT);
    $product_price = getFormValue(INPUT_POST, 'product_price', FILTER_DEFAULT);
    $product_quantity = getFormValue(INPUT_POST, 'product_quantity', FILTER_VALIDATE_INT);
    $seller_name = getFormValue(INPUT_POST, 'seller_name', FILTER_DEFAULT);
    $seller_city = getFormValue(INPUT_POST, 'seller_city', FILTER_DEFAULT);
    $seller_country = getFormValue(INPUT_POST, 'seller_country', FILTER_DEFAULT);

    // Access images from form data
    $product_image_name = $_FILES['product_image']['name'];
    $product_image_tmpname = $_FILES['product_image']['tmp_name'];
    $size = $_FILES['product_image']['size'];
    $type = $_FILES['product_image']['type'];

    if ($product_name == '' or $description == '' or $keywords == '' or $category == '' or $product_price == '' or $product_quantity == '' or $seller_name == '' or $seller_city == '' or $seller_country == '' or $product_image_name == '') {
        $_SESSION['input_message'] = 'Please fill all the fields!';
    } else {
        $pwd = dirname(getcwd(), 1);
        $file_destination = $pwd . '/images/product_images/' . $product_image_name;
        $file_ext = explode('.', $product_image_name);
        $file_actual_ext = strtolower(end($file_ext));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($file_actual_ext, $allowed)) {
            move_uploaded_file($product_image_tmpname, $file_destination);
            //add products into database
            $add_products_query = "INSERT into products (product_name, product_description, product_keywords, category, price, image, seller_name, city, country, quantity, seller_id) 
            VALUES 
            (:product_name, :product_description, :product_keywords, :category, :price, :image, :seller_name, :city, :country, :quantity, :seller_id)";
            $insert_stmt = $pdo->prepare($add_products_query);
            $insert_stmt->bindParam(':product_name', $product_name);
            $insert_stmt->bindParam(':product_description', $description);
            $insert_stmt->bindParam(':product_keywords', $keywords);
            $insert_stmt->bindParam(':category', $category);
            $insert_stmt->bindParam(':price', $product_price);
            $insert_stmt->bindParam(':image', $product_image_name);
            $insert_stmt->bindParam(':seller_name', $seller_name);
            $insert_stmt->bindParam(':city', $seller_city);
            $insert_stmt->bindParam(':country', $seller_country);
            $insert_stmt->bindParam(':quantity', $product_quantity);
            $insert_stmt->bindParam(':seller_id', $_SESSION['user_id']);
            $query_result = $insert_stmt->execute();
            if ($query_result == true) {
                $_SESSION['input_message'] = 'Product is successfully added into the database!';
            }
        } else {
            $_SESSION['input_message'] = 'Please upload file in jpg/jpeg or png format.';
        }
    }
    header("Location: add_products.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dukhan</title>
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- font awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS File -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="bg-light">
    <div class="container mt-3">
        <a href="../seller/seller_index.php" class="btn btn-info mb-3">Go Back</a>
        <h1 class="text-center">Add Products</h1>

        <?php
        if (isset($_SESSION['input_message'])) {
            $message = $_SESSION['input_message'];
            if (strpos($message, 'successfully')) {
                $class = 'text-center text-success';
            } else {
                $class = 'text-center text-danger';
            }
            echo ("<p class='" . $class . "' >" . $message . "</p>");
            unset($_SESSION['input_message']);
        }
        ?>

        <form action="./add_products.php" method="post" enctype="multipart/form-data">
            <!-- product name -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_name" class="form-label">Product name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" autocomplete="off" required>
            </div>

            <!-- description -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="description" class="form-label">Description</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="Enter product description" autocomplete="off" required>
            </div>

            <!-- key words -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="keywords" class="form-label">Key words</label>
                <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Enter key words" autocomplete="off" required>
            </div>

            <!-- category -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" id="category" class="form-control" placeholder="Enter category" autocomplete="off" required>
            </div>

            <!-- price -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_price" class="form-label">Price</label>
                <input type="number" name="product_price" id="product_price" class="form-control" placeholder="Enter product price" step="0.01" min=1 autocomplete="off" required>
            </div>

            <!-- quantity -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_quantity" class="form-label">Quantity</label>
                <input type="number" name="product_quantity" id="product_quantity" class="form-control" placeholder="Enter product quantity" min=1 autocomplete="off" required>
            </div>

            <!-- image -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image" class="form-label">Select Image</label>
                <input type="file" name="product_image" id="product_image" class="form-control" required>
            </div>

            <!-- Seller's information -->
            <h2 class="text-center">Seller's information</h2>
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="seller_name" class="form-label">Seller's name</label>
                <input type="text" name="seller_name" id="seller_name" class="form-control" placeholder="Enter seller's name" autocomplete="off" required>
            </div>

            <!-- city -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="seller_city" class="form-label">City</label>
                <input type="text" name="seller_city" id="seller_city" class="form-control" placeholder="Enter city name" autocomplete="off" required>
            </div>

            <!-- country -->
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="seller_country" class="form-label">Country</label>
                <input type="text" name="seller_country" id="seller_country" class="form-control" placeholder="Enter country name" autocomplete="off" required>
            </div>

            <!-- submit -->
            <div class="form-outline mb-4 w-50 m-auto text-center">
                <input type="submit" name="add_product" id="add_product" class="btn btn-info" value="Add product">
            </div>

        </form>
    </div>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>