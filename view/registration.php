<?php
include('../config/pdo.php');
include('../functions/common_functions.php');
session_start();

if (isset($_POST['user_register'])) {

    $firstname = getFormValue(INPUT_POST, 'firstname', FILTER_DEFAULT);
    $lastname = getFormValue(INPUT_POST, 'lastname', FILTER_DEFAULT);
    $email = getFormValue(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = getFormValue(INPUT_POST, 'password', FILTER_DEFAULT);
    $confirm_password = getFormValue(INPUT_POST, 'confirm_password', FILTER_DEFAULT);
    $contact = getFormValue(INPUT_POST, 'contact', FILTER_SANITIZE_NUMBER_INT);
    $register_as = getFormValue(INPUT_POST, 'register_as', FILTER_DEFAULT);

    // Validate email format
    if (!$email) {
        $_SESSION['input_message'] = 'Please enter a valid email address.';
        header("Location: registration.php");
        exit();
    } else if ($password !== $confirm_password) {
        $_SESSION['input_message'] = 'Password and Confirm Password do not match!';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO users (firstname, lastname, email, password, contact, register_as) 
                        VALUES (:firstname, :lastname, :email, :password, :contact, :register_as)";
        $insert_stmt = $pdo->prepare($insert_query);
        $insert_stmt->bindParam(':firstname', $firstname);
        $insert_stmt->bindParam(':lastname', $lastname);
        $insert_stmt->bindParam(':email', $email);
        $insert_stmt->bindParam(':password', $hashed_password);
        $insert_stmt->bindParam(':contact', $contact);
        $insert_stmt->bindParam(':register_as', $register_as);
        $query_result = $insert_stmt->execute();
        if ($query_result) {
            $_SESSION['input_message'] = 'User registered successfully!';
        } else {
            $_SESSION['input_message'] = 'Error registering user. Please try again.';
        }
    }
    header("Location: registration.php");
    return;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <h2 class="text-center">User Registration</h2>
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
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-12 col-xl-6">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-outline mb-4">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Your First Name" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Your Last Name" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" placeholder="Enter Your Email" autocomplete="off" required />
                        <p id="emailMsg"></p>
                    </div>

                    <div class="form-outline mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Your Password" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="tel" id="contact" name="contact" class="form-control" inputmode="numeric" pattern="[0-9]*" placeholder="Enter Your Mobile Number" autocomplete="off" required />
                    </div>

                    <div class="form-outline mb-4">
                        <label for="register_as" class="form-label">Register As</label>
                        <select id="register_as" name="register_as" class="form-control"  required>
                            <option value="" selected disabled>Select an option</option>
                            <option value="Customer">Customer</option>
                            <option value="Seller">Seller</option>
                        </select>
                    </div>

                    <div class="mt-4 pt-2">
                        <input type="submit" value="Create Account" class="bg-info py-2 px-3 border-0" name="user_register" />
                        <p class="small fw-bold mt-2 pt-1 mb-0">Already have an account ? <a href="login.php">Login </a></p>
                    </div>

                </form>

            </div>
        </div>
    </div>
</body>

</html>

<!-- Javascript code to validate email is exisiting user or new -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#email').on('blur', function() {
            event.preventDefault();
            var email = $(this).val();
            if (email.trim() !== "") {
                $.getJSON('../config/getjson.php', {
                    email: email
                }, function(data) {
                    if (data.email_exists) {
                        $('#emailMsg').text('Account already exists with this email. Please use a different email').css('color', 'red').show();
                    } else {
                        $('#emailMsg').text('');
                    }
                });
            }
        });
    });
</script>