<?php
include('../config/pdo.php');

//function to update welcome message 
function updateWelcomeMessage()
{
    $user_firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
    $user_lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : null;
    $welcome_message = "Welcome, Guest!";
    if ($user_firstname !== null && $user_lastname !== null) {
        $welcome_message = "Welcome, $user_firstname $user_lastname!";
    }
    echo $welcome_message;
}

//function to update login/logout text in home screen
function updateLoginLogout()
{
    $user_firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : null;
    $user_lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : null;
    $login_logout_text = "Login/Register";
    if ($user_firstname !== null && $user_lastname !== null) {
        $login_logout_text = "Logout";
    }
    echo $login_logout_text;
}

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
        $select_query = "SELECT * from products order by rand()";
        $stmt = $pdo->query($select_query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                            <a href='./view/cart.php?cart=$product_id' class='btn btn-secondary'>Add to cart</a>
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
                                <a href='./view/cart.php?cart=$product_id' class='btn btn-secondary'>Add to cart</a>
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
                <a href='cart.php?cart=$product_id' class='btn btn-lg btn-info my-4'>Add to cart</a>
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
    if (isset($_GET['cart'])) {

        if (isset($_SESSION['user_id'])) {
            global $pdo;
            $product_id = getFormValue(INPUT_GET, 'cart', FILTER_DEFAULT);
            $userID = $_SESSION['user_id'];
            $select_query = "SELECT *
        FROM cart
        JOIN users ON cart.user_id = users.user_id
        JOIN products AS cart_products ON cart.product_id = cart_products.product_id
        WHERE cart_products.product_id = $product_id AND users.user_id = $userID;";
            $stmt = $pdo->query($select_query);
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (sizeof($row) > 0) {
                echo "<script>alert('This item is already in the cart')</script>";
                echo "<script>window.open('../index.php', '_self')</script>";
            } else {
                $insertStmt = "INSERT into cart (user_id, product_id) values ($userID, $product_id)";
                $stmt = $pdo->prepare($insertStmt);
                $stmt->execute();
                echo "<script>alert('You have successfully added the item into cart')</script>";
                echo "<script>window.open('../index.php', '_self')</script>";
            }
        } else {
            echo "<script>alert('Please login to add items into the cart.')</script>";
            echo "<script>window.open('login.php', '_self')</script>";
        }
    }
}

//number on cart
function cartNumber()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $userID = $_SESSION['user_id'];
        $select_query = "select * from cart where user_id = :userID";
        $stmt =  $pdo->prepare($select_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo sizeof($rows);
    }
}

//get cart items
function getCartItems()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $userID = $_SESSION['user_id'];
        $item_prices = [];
        $select_query = "SELECT product_id, cart_quantity from cart where user_id = :userID";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_id = $row['product_id'];
                $quantity = $row['cart_quantity'];
                $item_query = "SELECT * from products where product_id = :product_id";
                $itemstmt = $pdo->prepare($item_query);
                $itemstmt->bindParam(':product_id', $product_id);
                $itemstmt->execute();
                $res = $itemstmt->fetch(PDO::FETCH_ASSOC);
                $product_name = $res['product_name'];
                $product_description = $res['product_description'];
                $img = $res['image'];
                $price = $quantity * $res['price'];
                $item_prices[] = $price;
                $_SESSION['quantity'] = $res['quantity'];
                echo ("
        <div class='col-md-3 mb-2'>
                    <div class='card'>
                        <img src='../images/product_images/$img' class='card-img-top' alt='$product_name'>
                        <div class='card-body'>
                            <h5 class='card-title'>$product_name</h5>
                            <p class='card-text'>$product_description</p>
                            <p class='card-text'>Price: $$price</p>
                            <form method='post' action='cart.php?productID=$product_id&userID=$userID' class='d-flex'>
                            <label for='quantity'>Quantity:</label>
                            <input type='number' name='quantity' id='quantity' value='$quantity' class='quan mx-1 mb-3' min=1 autocomplete='off'>
                            <input type='submit' name='update_cart' id='update_cart' class='quan' value='Update'>
                            </form>
                            <a href='cart.php?remove_cart=$product_id&userID=$userID' class='btn btn-info mx-1'>Remove</a>
                            <a href='product_details.php?product_id=$product_id' class='btn btn-secondary'>View Details</a>
                        </div>
                    </div>
                </div>
                ");
            }
            $total_price = array_sum($item_prices);
            $_SESSION['total_price'] = $total_price;
            echo ("<form method='post' action='../index.php' class='text-center d-flex'>
                            <h2 class='text-info text-center m-3'>Total price: $$total_price </h2>
                            <input type='submit' name='countinue_shopping' id='countinue_shopping' class='bg-info px-3 py-2 border-0 m-3' value='Continue Shopping'>
                            <button class='bg-info px-3 py-2 border-0 m-3'><a href='checkout.php' class='text-dark text-decoration-none'>checkout</a></button>
                            </form>");
        } else {
            echo ("<h1 class='text-center text-danger'>You don't have any items in your cart!</h1>");
        }
    } else {
        echo "<script>alert('Please login to access the cart.')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
    }
}

//remove from cart
function removeFromCart()
{
    if (isset($_GET['remove_cart']) and isset($_GET['userID'])) {
        global $pdo;
        $userID = getFormValue(INPUT_GET, 'userID', FILTER_DEFAULT);
        $productID = getFormValue(INPUT_GET, 'remove_cart', FILTER_DEFAULT);
        $select_query = "DELETE from cart where user_id = :userID and product_id = :productID";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->bindParam(':productID', $productID);
        $stmt->execute();
        header('Location: cart.php');
        exit();
    }
}

function updateQuantityInCart()
{
    if (isset($_POST['update_cart'])) {
        global $pdo;
        $userID = getFormValue(INPUT_GET, 'userID', FILTER_VALIDATE_INT);
        $productID = getFormValue(INPUT_GET, 'productID', FILTER_VALIDATE_INT);
        $quantity = getFormValue(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        $available = $_SESSION['quantity'];
        if ($quantity > $available) {
            echo "<script>alert('Total available items is $available. Please select lesser value')</script>";
        } else {
            $update_query = "UPDATE cart set cart_quantity=:quantity where user_id=:userID and product_id=:productID";
            $update = $pdo->prepare($update_query);
            $update->bindParam(':quantity', $quantity);
            $update->bindParam(':userID', $userID);
            $update->bindParam(':productID', $productID);
            $update->execute();
            echo "<script>window.open('cart.php', '_self')</script>";
        }
    }
}

function getCheckoutItems()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $userID = $_SESSION['user_id'];
        $item_prices = [];
        $select_query = "SELECT product_id, cart_quantity from cart where user_id = :userID";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product_id = $row['product_id'];
                $quantity = $row['cart_quantity'];
                $item_query = "SELECT * from products where product_id = :product_id";
                $itemstmt = $pdo->prepare($item_query);
                $itemstmt->bindParam(':product_id', $product_id);
                $itemstmt->execute();
                $res = $itemstmt->fetch(PDO::FETCH_ASSOC);
                $product_name = $res['product_name'];
                $product_description = $res['product_description'];
                $img = $res['image'];
                $price = $quantity * $res['price'];
                $item_prices[] = $price;
                $_SESSION['quantity'] = $res['quantity'];
                echo ("<div class='card mb-3'>
                                        <div class='card-body'>
                                            <div class='d-flex justify-content-between'>
                                                <div class='d-flex flex-row align-items-center'>
                                                    <div>
                                                        <img src='../images/product_images/$img' class='img-fluid rounded-3' alt='Shopping item' style='width: 65px;'>
                                                    </div>
                                                    <div class='ms-3'>
                                                        <h5>$product_name</h5>
                                                        <p class='small mb-0'>$product_description</p>
                                                    </div>
                                                </div>
                                                <div class='d-flex flex-row align-items-center'>
                                                    <div style='width: 50px;'>
                                                        <h5 class='fw-normal mb-0'>$quantity</h5>
                                                    </div>
                                                    <div style='width: 80px;'>
                                                        <h5 class='mb-0'>$$price</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>");
            }
            $total_price = array_sum($item_prices);
            $_SESSION['total_price'] = $total_price;
        } else {
        }
    } else {
        echo "<script>alert('Please login to access the checkout page.')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
    }
}

function getLastOrderedItems()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $payment_id = $_SESSION['payment_id'];
        $select_query = "SELECT * FROM `orders`
         JOIN products ON orders.product_id = products.product_id
          JOIN payments on orders.pay_id = payments.payment_id
           WHERE orders.pay_id = :payment_id;";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':payment_id', $payment_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quantity = $row['order_quantity'];
                $product_name = $row['product_name'];
                $product_description = $row['product_description'];
                $img = $row['image'];
                $price = $row['price'];
                $item_total_price = $quantity * $price;
                echo ("<div class='card mb-3'>
                                                <div class='card-body'>
                                                    <div class='d-flex justify-content-between'>
                                                        <div class='d-flex flex-row align-items-center'>
                                                            <div>
                                                                <img src='../images/product_images/$img' class='img-fluid rounded-3' alt='Shopping item' style='width: 65px;'>
                                                            </div>
                                                            <div class='ms-3'>
                                                                <h5>$product_name</h5>
                                                                <p class='small mb-0'>$product_description</p>
                                                            </div>
                                                        </div>
                                                        <div class='d-flex flex-row align-items-center'>
                                                            <div style='width: 50px;'>
                                                                <h5 class='fw-normal mb-0'>$quantity</h5>
                                                            </div>
                                                            <div style='width: 80px;'>
                                                                <h5 class='mb-0'>$$item_total_price</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>");
            }
            unset($_SESSION['payment_id']);
        }
    } else {
        echo "<script>alert('Please login to access the checkout page.')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
    }
}

function getYourOrders()
{
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $user_id = $_SESSION['user_id'];
        $select_query = "SELECT * FROM `orders`
         JOIN products ON orders.product_id = products.product_id
          JOIN payments on orders.pay_id = payments.payment_id
           WHERE orders.user_id = :user_id order by orders.order_date asc;";
        $stmt = $pdo->prepare($select_query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $quantity = $row['order_quantity'];
                $product_name = $row['product_name'];
                $product_description = $row['product_description'];
                $img = $row['image'];
                $price = $row['price'];
                $item_total_price = $quantity * $price;
                echo ("<div class='card mb-3'>
                                                <div class='card-body'>
                                                    <div class='d-flex justify-content-between'>
                                                        <div class='d-flex flex-row align-items-center'>
                                                            <div>
                                                                <img src='../images/product_images/$img' class='img-fluid rounded-3' alt='Shopping item' style='width: 65px;'>
                                                            </div>
                                                            <div class='ms-3'>
                                                                <h5>$product_name</h5>
                                                                <p class='small mb-0'>$product_description</p>
                                                            </div>
                                                        </div>
                                                        <div class='d-flex flex-row align-items-center'>
                                                            <div style='width: 50px;'>
                                                                <h5 class='fw-normal mb-0'>$quantity</h5>
                                                            </div>
                                                            <div style='width: 80px;'>
                                                                <h5 class='mb-0'>$$item_total_price</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>");
            }
            unset($_SESSION['payment_id']);
        } else {
            echo "<h5 class='text-center text-danger'>You haven't placed any orders yet!</h5>";
        }
    } else {
        echo "<script>alert('Please login to access the Orders page.')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
    }
}
