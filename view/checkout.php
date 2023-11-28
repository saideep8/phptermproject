<?php
session_start();
include('./config/pdo.php');
include('./functions/common_functions.php');
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
    <!-- Navigation bar -->
    <div class="container-fluid p-0">

        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">
                <img src="../images/dukhan.png" alt="" class="logo">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Products</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i><sup></sup></a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="registration.php">Login/Register</a>
                        </li>

                    </ul>
                    <form class="d-flex" action="index.php" method="get">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search_data">
                        <input class="btn btn-outline-light" value="Search" type="submit" name="search_data_product">
                    </form>
                </div>
            </div>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome Guest</a>
                </li>
            </ul>
        </nav>

        <div class="bg-light p-1">
            <h3 class="text-center">Shop with Dukhan.</h3>
            <p class="text-center">Hassle free!</p>
        </div>
    </div>

    <div class="row px-1">
        <div class="col-md-12">
            <div class="row">
                <!-- php code -->
                <?php
                unset($_SESSION['user_id']);
                if (isset($_SESSION['user_id'])) {
                    echo "inside...";
                    echo $_SESSION['user_id'];
                } else {
                    include('login.php');
                }
                ?>
            </div>
        </div>
    </div>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>

</html>