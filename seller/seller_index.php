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
    <title>Admin dashboard</title>
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- CSS File -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container-fluid p-0">

        <nav class="navbar navbar-expand-lg navbar-light bg-info">
            <div class="container-fluid">
                <img src="../images/dukhan.png" alt="logo" class="logo">
                <nav class="navbar navbar-expand-lg">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <!-- php code -->
                                <?php
                                updateWelcomeMessage();
                                ?>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>


        <div class="bg-light">
            <h3 class="text-center p-2">Manage details</h3>
        </div>

        <div class="row">
            <div class="col-md-12 bg-secondary p-1 d-flex align-items-center">
                <div class="px-5">
                    <!-- <a href="#"><img src="../images/apple.jpeg" alt="apple" class="admin-image"></a> -->
                    <p class="text-light text-center">Admin name</p>
                </div>
                <div class="button text-center">
                    <button><a href="../view/add_products.php" class="nav-link text-light bg-info my-1">Add products</a></button>
                    <button><a href="" class="nav-link text-light bg-info my-1">View products</a></button>
                    <button><a href="" class="nav-link text-light bg-info my-1">All orders</a></button>
                    <button>
                        <a class="nav-link text-light bg-info my-1" href="<?php echo isset($_SESSION['firstname']) ? '../view/logout.php' : '../view/login.php'; ?>">
                            <?php
                            updateLoginLogout();
                            ?>
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>