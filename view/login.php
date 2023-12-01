<?php

session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

//function to increment login count
function incrementLoginCount()
{
    if (isset($_COOKIE["login_count_" . $_SESSION['user_id']])) {
        $loginCount =  $_COOKIE["login_count_" . $_SESSION['user_id']];
    } else {
        $loginCount = 0;
    }
    $loginCount++;
    setcookie("login_count_" . $_SESSION['user_id'], $loginCount, 86400, "/");
}

if (isset($_POST['user_login'])) {
    $email = getFormValue(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = getFormValue(INPUT_POST, 'password', FILTER_DEFAULT);

    $select_user_query = "SELECT * FROM users WHERE email = :email";
    $select_user_stmt = $pdo->prepare($select_user_query);
    $select_user_stmt->bindParam(':email', $email);
    $select_user_stmt->execute();
    $user_data = $select_user_stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        if (password_verify($password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['email'] = $user_data['email'];
            $_SESSION['firstname'] = $user_data['firstname'];
            $_SESSION['lastname'] = $user_data['lastname'];
            $_SESSION['register_as'] = $user_data['register_as'];
            incrementLoginCount();
            if ($_SESSION['register_as'] === "Customer") {
                header("Location: ../index.php");
            } else {
                header("Location: ../seller/seller_index.php");
            }
            $_SESSION['login_message'] = 'Logged in successfully';
            exit();
        } else {
            $_SESSION['login_message'] = 'Incorrect password. Please try again.';
        }
    } else {
        $_SESSION['login_message'] = 'No user found with this email.';
    }

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <h2 class="text-center">User Login</h2>
        <?php
        if (isset($_SESSION['login_message'])) {
            $message = $_SESSION['login_message'];
            if (strpos($message, 'successfully')) {
                $class = 'text-center text-success';
            } else {
                $class = 'text-center text-danger';
            }
            echo ("<p class='" . $class . "' >" . $message . "</p>");
            unset($_SESSION['login_message']);
        }
        ?>
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-12 col-xl-6">
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter Your Email" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Your Password" autocomplete="off" required />
                    </div>

                    <div class="mt-4 pt-2">
                        <input type="submit" value="Login" class="bg-info py-2 px-3 border-0" name="user_login" />
                        <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account ?
                            <a href="registration.php"> Register </a>
                        </p>
                    </div>

                </form>

            </div>
        </div>
    </div>
</body>

</html>