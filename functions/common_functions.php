<?php
include('./config/pdo.php');
session_start();

//function to get form inputs and sanitize them
function getFormValue($method, $form_id, $filter)
{
    $value = filter_input($method, $form_id, $filter);
    $result = trim(htmlentities(strip_tags($value)));
    return $result;
}

//Get products
function getProducts()
{
    if (!isset($_GET['search_data_product'])) {
        global $pdo;
        $select_query = "SELECT * from products order by rand() LIMIT 0,12";
        $stmt = $pdo->query($select_query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userID = $_SESSION['user_id'];
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_description = $row['product_description'];
            $img = $row['image'];
            $price = $row['price'];
            echo ("<div class='col-md-3 mb-2'>
                    <div class='card'>
                        <img src='./images/product_images/$img' class='card-img-top' alt='$product_name'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_name</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>Price: $$price</p>
                            <a href='./view/cart.php?cart=$product_id&userID=$userID' class='btn btn-info'>Add to cart</a>
                            <a href='./view/product_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                        </div>
                    </div>
                </div>");
        }
    }
}

// get Search products
function getSearchProducts()
{
    global $pdo;
    if (isset($_GET['search_data_product'])) {
        $search_value = getFormValue(INPUT_GET, 'search_data', FILTER_DEFAULT);
        $search_query = "SELECT * from products where product_keywords LIKE '%$search_value%'";
        $stmt = $pdo->prepare($search_query);
        $stmt->execute();
        $count = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($count) > 0) {
            $search_query = "SELECT * from products where product_keywords LIKE '%$search_value%'";
            $stmt = $pdo->prepare($search_query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userID = $_SESSION['user_id'];
                $product_id = $row['product_id'];
                $product_name = $row['product_name'];
                $product_description = $row['product_description'];
                $img = $row['image'];
                $price = $row['price'];
                echo ("<div class='col-md-3 mb-2'>
                        <div class='card'>
                            <img src='./images/product_images/$img' class='card-img-top' alt='$product_name'>
                            <div class='card-body'>
                                <h5 class='card-title'>$product_name</h5>
                                <p class='card-text'>$product_description</p>
                                <p class='card-text'>Price: $$price</p>
                                <a href='./view/cart.php?cart=$product_id&userID=$userID' class='btn btn-info'>Add to cart</a>
                                <a href='./view/product_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                            </div>
                        </div>
                    </div>");
            }
        } else {
            echo ("<h2 class='text-center text-danger'>Oops! We cannot find the product you're looking for.</h2>");
        }
    }
}

//get product details
function getProductDetails()
{
    if (isset($_GET['product_id'])) {
        global $pdo;
        $product_id = getFormValue(INPUT_GET, 'product_id', FILTER_DEFAULT);
        $select_query = "SELECT * from products WHERE product_id = :product_id";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userID = $_SESSION['user_id'];
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_description = $row['product_description'];
            $img = $row['image'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $seller_name = $row['seller_name'];
            $category = $row['category'];

            echo ("<div class='text-center'>

            <div class='card-body'>
            <div class='row'>
                <div class='col-md-5 px-1'>
                <img src='../images/product_images/$img' class='img' alt='$product_name'>
                </div>
                <div class='col-md-5 my-5 py-5'>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Product name:</h5>
                <h5 class='text-start '>$product_name</h5>
                </div>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Description:</h5>
                <h5 class='text-start '>$product_description</h5>
                </div>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Price:</h5>
                <h5 class='text-start '>$$price</h5>
                </div>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Stock left:</h5>
                <h5 class='text-start '>$quantity</h5>
                </div>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Category:</h5>
                <h5 class='text-start '>$category</h5>
                </div>
                <div class='d-flex'>
                <h5 class='text-start text-primary mx-2'>Sold by:</h5>
                <h5 class='text-start '>$seller_name</h5>
                </div>
                <div class='d-flex mx-2'>
                <a href='cart.php?cart=$product_id&userID=$userID' class='btn btn-lg btn-info my-4'>Add to cart</a>
                </div>
                </div>
                </div>
                </div>
    
        </div>");
        }
    }
}

//Add product to cart implementation
function addProductToCart()
{
    if (isset($_GET['cart']) and isset($_GET['userID'])) {
        global $pdo;
        $product_id = getFormValue(INPUT_GET, 'cart', FILTER_DEFAULT);
        $userID = getFormValue(INPUT_GET, 'userID', FILTER_DEFAULT);
        $select_query = "SELECT *
        FROM cart
        JOIN users ON cart.user_id = users.user_id
        JOIN products AS cart_products ON cart.product_id = cart_products.product_id
        WHERE cart_products.product_id = $product_id AND users.user_id = $userID;";
        $stmt = $pdo->query($select_query);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (sizeof($row) > 0) {
            // echo ("<div class='toast' role='alert' aria-live='assertive' aria-atomic='true'>
            //     <div class='toast-header'>
            //       <strong class='me-auto text-danger'>Warning</strong>
            //       <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
            //     </div>
            //     <div class='toast-body'>
            //       This product is already in the cart!
            //     </div>
            //   </div>");
            echo "<script>alert('This item is already in the cart')</script>";
            echo "<script>window.open('../index.php', '_self')</script>";
            // return;
            // echo ("<p>This product is already in the cart</p>");
        } else {
            $insertStmt = "INSERT into cart (user_id, product_id) values ($userID, $product_id)";
            $stmt = $pdo->prepare($insertStmt);
            $stmt->execute();
            echo "<script>alert('You have successfully added the item into cart')</script>";
            // echo "<script>window.open('../index.php', '_self')</script>";
        }
    }
}

//number on cart
function cartNumber()
{
    global $pdo;
    $userID = $_SESSION['user_id'];
    $select_query = "select * from cart where user_id = :userID";
    $stmt =  $pdo->prepare($select_query);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo sizeof($rows);
}

//get cart items
function getCartItems()
{
    // if (!isset($_GET['search_data_product'])) {
    global $pdo;
    $userID = $_SESSION['user_id'];
    $item_prices = [];
    $select_query = "SELECT product_id from cart where user_id = :userID";
    $stmt = $pdo->prepare($select_query);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $product_id = $row['product_id'];
        $item_query = "SELECT * from products where product_id = :product_id";
        $itemstmt = $pdo->prepare($item_query);
        $itemstmt->bindParam(':product_id', $product_id);
        $itemstmt->execute();
        $res = $itemstmt->fetch(PDO::FETCH_ASSOC);
        $product_name = $res['product_name'];
        $product_description = $res['product_description'];
        $img = $res['image'];
        $price = $res['price'];
        $item_prices[] = $price;
        echo ("<div class='col-md-3 mb-2'>
                    <div class='card'>
                        <img src='../images/product_images/$img' class='card-img-top' alt='$product_name'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_name</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>Price: $$price</p>
                            <a href='./view/product_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                        </div>
                    </div>
                </div>");
    }

    $total_price = array_sum($item_prices);
    echo ("<h1 class='text-success text-center m-3'>Total price: $$total_price</h1>");
    // }
}

//remove from cart
function removeFromCart()
{
    if (isset($_GET['remove_cart'])) {
        global $pdo;
        $userID = $_SESSION['user_id'];
        $item_prices = [];
        $select_query = "SELECT product_id from cart where user_id = :userID";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $row['product_id'];
            $item_query = "SELECT * from products where product_id = :product_id";
            $itemstmt = $pdo->prepare($item_query);
            $itemstmt->bindParam(':product_id', $product_id);
            $itemstmt->execute();
            $res = $itemstmt->fetch(PDO::FETCH_ASSOC);
            $product_name = $res['product_name'];
            $product_description = $res['product_description'];
            $img = $res['image'];
            $price = $res['price'];
            $item_prices[] = $price;
            echo ("<div class='col-md-3 mb-2'>
                        <div class='card'>
                            <img src='../images/product_images/$img' class='card-img-top' alt='$product_name'>
                            <div class='card-body'>
                                <h5 class='card-title'>$product_name</h5>
                                <p class='card-text'>$product_description</p>
                                <p class='card-text'>Price: $$price</p>
                                <a href='./view/cart.php?cart=$product_id&userID=$userID' class='btn btn-info'>Add to cart</a>
                                <a href='./view/product_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                            </div>
                        </div>
                    </div>");
        }
    }
}
