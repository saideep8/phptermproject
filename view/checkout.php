<?php

session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

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

                                <div class="col-lg-7">
                                    <h5 class="mb-3"><a href="../index.php" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a></h5>
                                    <hr>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="mb-1">Shopping cart</p>
                                            <p class="mb-0">You have <?php cartNumber(); ?> items in your cart</p>
                                        </div>

                                    </div>

                                    <?php
                                    getCheckoutItems();
                                    ?>
                                </div>
                                <div class="col-lg-5">

                                    <div class="card bg-primary text-white rounded-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5 class="mb-0">Card details</h5>
                                            </div>

                                            <form action="payment.php" method="post" class="mt-4">
                                                <div class="form-outline form-white mb-4">
                                                    <input type="text" id="cardholder" name="cardholder" class="form-control form-control-lg" siez="17" placeholder="Cardholder's Name" required />
                                                    <label class="form-label" for="cardholder">Cardholder's Name</label>
                                                </div>

                                                <div class="form-outline form-white mb-4">
                                                    <input type="tel" id="cardnumber" name="cardnumber" class="form-control form-control-lg" siez="17" placeholder="Enter your card number" pattern="[0-9\s]{1,16}" minlength="16" maxlength="16" required />
                                                    <label class="form-label" for="cardnumber">Card Number</label>
                                                </div>

                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <div class="form-outline form-white">
                                                            <input type="tel" id="expirydate" name="expirydate" class="form-control form-control-lg" placeholder="MMYYYY" size="1" id="exp" minlength="6" maxlength="6" pattern="[0-9\s]{1,6}" required />
                                                            <label class="form-label" for="expirydate">Expiry date</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-outline form-white">
                                                            <input type="tel" id="cvv" name="cvv" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" minlength="3" maxlength="3" pattern="[0-9\s]{1,6}" required />
                                                            <label class="form-label" for="cvv">CVV</label>
                                                        </div>
                                                    </div>
                                                </div>



                                                <hr class="my-4">

                                                <div class="d-flex justify-content-between">
                                                    <p class="mb-2">Subtotal</p>
                                                    <p class="mb-2">$<?php echo ($_SESSION['total_price']); ?></p>
                                                </div>

                                                <div class="d-flex justify-content-between mb-4">
                                                    <p class="mb-2">Total(Incl. taxes)</p>
                                                    <p class="mb-2">$<?php echo ($_SESSION['total_price']); ?> </p>
                                                </div>
                                                <input type="submit" name="payment" id="payment" class="btn btn-info btn-block btn-lg" value="Proceed to payment">
                                            </form>
                                        </div>
                                    </div>

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