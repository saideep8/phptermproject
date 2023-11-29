<?php

session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

if (isset($_POST['payment']) && isset($_SESSION['user_id'])) {

    $user_id = $_SESSION['user_id'];
    $card_holder = getFormValue(INPUT_POST, 'cardholder', FILTER_DEFAULT);
    $card_number = getFormValue(INPUT_POST, 'cardnumber', FILTER_VALIDATE_INT);
    $expiry_date = getFormValue(INPUT_POST, 'expirydate', FILTER_VALIDATE_INT);
    $cvv = getFormValue(INPUT_POST, 'cvv', FILTER_VALIDATE_INT);

    $insertStmt = "INSERT into payments (user_id, card_name, card_number, expiry_date, cvv) values (:user_id, :card_holder, :card_number, :expiry_date, :cvv)";
    $stmt = $pdo->prepare($insertStmt);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':card_holder', $card_holder);
    $stmt->bindParam(':card_number', $card_number);
    $stmt->bindParam(':expiry_date', $expiry_date);
    $stmt->bindParam(':cvv', $cvv);
    $stmt->execute();
    $payment_id = $pdo->lastInsertId();
    $_SESSION['payment_id'] = $payment_id;
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

<body>
    <section style="background-color: white; height:100%;">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card rounded-3">
                        <div class="card-body mx-1 my-2">

                            <div class="pt-3">
                                <div class="d-flex flex-row pb-3 align-items-center">
                                    <div>
                                        <i class="fab fa-cc-visa fa-4x text-black pe-3"></i>
                                    </div>
                                    <div>
                                        <p class="d-flex flex-column mb-0">
                                            <b><?php echo ($card_holder) ?></b><span class="small text-muted">**** <?php echo (substr($card_number, -4)) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="d-flex flex-row pb-3">
                                    <div class="rounded border border-primary border-2 d-flex w-100 p-3 align-items-center" style="background-color: rgba(18, 101, 241, 0.07);">
                                        <div class="d-flex align-items-center pe-3">
                                            <input class="form-check-input" type="radio" name="radioNoLabelX" id="radioNoLabel11" value="" aria-label="..." checked />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <p class="mb-1 small text-primary">Total amount due</p>
                                            <h6 class="mb-0 text-primary"><?php echo $_SESSION['total_price'] ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pb-1">
                                <a href="checkout.php" class="text-muted">Go back</a>
                                <form action="order_confirmation.php" method="post">
                                    <input type="submit" class="btn btn-primary btn-lg" value="Pay amount">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>