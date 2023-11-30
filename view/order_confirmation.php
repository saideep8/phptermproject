<?php

session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

if (isset($_POST['pay_amount'])) {

    $user_id = $_SESSION['user_id'];
    $payment_id = $_SESSION['payment_id'];

    $timestamp = time();
    $order_date = date('Y-m-d H:i:s', $timestamp);

    //get payment details
    $select_payments_query = "SELECT * FROM payments where payment_id = :payment_id";
    $payments_stmt = $pdo->prepare($select_payments_query);
    $payments_stmt->bindParam(':payment_id', $payment_id);
    $payments_stmt->execute();
    $payments_row = $payments_stmt->fetch(PDO::FETCH_ASSOC);
    $card_holder = $payments_row['card_name'];
    $card_number = $payments_row['card_number'];

    //get items in cart
    $select_cart_query = "SELECT product_id, cart_quantity FROM cart where user_id = :user_id";
    $cart_stmt = $pdo->prepare($select_cart_query);
    $cart_stmt->bindParam(':user_id', $user_id);
    $cart_stmt->execute();
    $count = $cart_stmt->rowCount();

    if ($count > 0) {
        while ($cart_row = $cart_stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_id = $cart_row['product_id'];
            $quantity = $cart_row['cart_quantity'];

            //Insert into orders table
            $insert_order_stmt = "INSERT into orders (pay_id, user_id, product_id, order_quantity, order_date) values (:payment_id, :user_id, :product_id, :quantity, :order_date)";
            $order_stmt = $pdo->prepare($insert_order_stmt);
            $order_stmt->bindParam(':payment_id', $payment_id);
            $order_stmt->bindParam(':user_id', $user_id);
            $order_stmt->bindParam(':product_id', $product_id);
            $order_stmt->bindParam(':quantity', $quantity);
            $order_stmt->bindParam(':order_date', $order_date);
            $order_stmt->execute();

            //Remove ordered items from cart
            $delete_query = "DELETE from cart where user_id = :user_id and product_id = :product_id";
            $delete_stmt = $pdo->prepare($delete_query);
            $delete_stmt->bindParam(':user_id', $user_id);
            $delete_stmt->bindParam(':product_id', $product_id);
            $delete_stmt->execute();
        }
    }
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
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <section class="h-100 h-custom" style="background-color: white;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="row">

                                <div class="col-lg-12">
                                    <h5 class="mb-3"><a href="../index.php" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a></h5>
                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <h3 class="mb-1 text-success">Congrats!</h3>
                                            <p class="mb-0 text-success">Your order is successful.</p>
                                            <p class="mb-0 text-success">Total Price: $
                                                <?php echo $_SESSION['total_price'];
                                                unset($_SESSION['total_price']);
                                                ?>
                                            </p>
                                        </div>

                                    </div>

                                    <?php
                                    getLastOrderedItems();
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>