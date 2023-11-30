<?php
session_start();
include('../config/pdo.php');
include('../functions/common_functions.php');

if (!isset($_SESSION['register_as']) || $_SESSION['register_as'] !== 'Seller') {
    echo "<script>alert('Please login as a seller')</script>";
    echo "<script>window.open('../view/logout.php', '_self')</script>";
    exit();
}

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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="#" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="../view/add_products.php" class="nav-link">Add products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($_SESSION['firstname']) ? '../view/logout.php' : '../view/login.php'; ?>">
                                <?php
                                updateLoginLogout();
                                ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <p class="nav-text">
                    <a href="" class="nav-link">
                        <!-- php code -->
                        <?php
                        updateWelcomeMessage();
                        ?>
                    </a>
                </p>
            </div>
        </nav>
        <!-- View Products Table Starts Here -->
        <div class="container mt-5">
            <p class='msg text-center'></p>
            <tbody>
                <?php
                include('../config/pdo.php');
                $user_id = $_SESSION['user_id'];
                $select_query = "SELECT * FROM products WHERE seller_id = :seller_id";
                $stmt = $pdo->prepare($select_query);
                $stmt->bindParam(':seller_id', $user_id);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count > 0) {
                    echo "<table class='table table-bordered' id=productsTable'>";

                    echo   "<thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr class='view-mode' data-product-id='{$row['product_id']}'>";
                        echo "<td>{$row['product_id']}</td>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['product_description']}</td>";
                        echo "<td>{$row['category']}</td>";
                        echo "<td>{$row['price']}</td>";
                        echo "<td>{$row['quantity']}</td>";
                        echo "<td><img src='../images/product_images/{$row['image']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                        echo "<td><button class='btn btn-info edit-btn'>Edit</button> <a href='../config/delete_product.php?id={$row['product_id']}' class='btn btn-info'>Delete</a></td>";
                        echo "</tr>";

                        // Hidden row with input fields for editing
                        echo "<tr class='edit-mode' data-product-id='{$row['product_id']}' style='display: none;'>";
                        echo "<td>{$row['product_id']}</td>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$row['product_description']}</td>";
                        echo "<td>{$row['category']}</td>";
                        // echo "<form action='' method='post'>";
                        echo "<td><input type='number' value='{$row['price']}' step='0.01' min=1 name='edit_price' required></td>";
                        echo "<td><input type='number' value='{$row['quantity']}' min=1 name='edit_quantity' required></td>";
                        echo "<td><img src='../images/product_images/{$row['image']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                        // echo "<td><input type='submit' value='save' class='btn btn-success save-btn'><input type='submit' value='delete' class='btn btn-danger save-btn'</td>";
                        echo "<td><button class='btn btn-success save-btn'>Save</button><button class='btn btn-danger cancel-btn'>Cancel</button></td>";
                        // echo "</form>";
                        echo "</tr>";
                    }
                    echo "</tbody>
                    </table>";
                } else {
                    echo "<h2 class='text-center text-danger'>No products added yet to display!</h2>";
                }
                ?>

        </div>
    </div>

    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Edit button click event
            $('.edit-btn').on('click', function() {
                var productId = $(this).closest('tr').data('product-id');
                $('tr.view-mode[data-product-id="' + productId + '"]').hide();
                $('tr.edit-mode[data-product-id="' + productId + '"]').show();
            });

            // Cancel button click event
            $('.cancel-btn').on('click', function() {
                var productId = $(this).closest('tr').data('product-id');
                $('tr.edit-mode[data-product-id="' + productId + '"]').hide();
                $('tr.view-mode[data-product-id="' + productId + '"]').show();
            });

            // Save button click event
            $('.save-btn').on('click', function() {
                var productId = $(this).closest('tr').data('product-id');
                var newPrice = $('tr.edit-mode[data-product-id="' + productId + '"] input[name="edit_price"]').val();
                var newQuantity = $('tr.edit-mode[data-product-id="' + productId + '"] input[name="edit_quantity"]').val();

                // Post call to update products data
                $.post({
                    url: '../config/update_product.php',
                    data: {
                        product_id: productId,
                        new_price: newPrice,
                        new_quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Data updated successfully:', response);
                            window.location.href = '../seller/seller_index.php';
                            $('tr.edit-mode[data-product-id="' + productId + '"]').hide();
                            $('tr.view-mode[data-product-id="' + productId + '"]').show();
                            $('.msg').empty().hide();
                        } else {
                            $('.msg').text('Please enter proper values').css('color', 'red').show();

                        }

                    },
                    error: function(error) {
                        console.error('Error updating data:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>