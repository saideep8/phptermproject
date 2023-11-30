<?php
require_once "../config/pdo.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $productId = getFormValue(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $check_cart_query = "SELECT * FROM cart WHERE product_id = :product_id";
    $check_cart_stmt = $pdo->prepare($check_cart_query);
    $check_cart_stmt->bindParam(':product_id', $productId);
    $check_cart_stmt->execute();

    if ($check_cart_stmt->rowCount() > 0) {
        $delete_cart_query = "DELETE FROM cart WHERE product_id = :product_id";
        $delete_cart_stmt = $pdo->prepare($delete_cart_query);
        $delete_cart_stmt->bindParam(':product_id', $productId);
        $delete_cart_stmt->execute();
    }

    $delete_product_query = "DELETE FROM products WHERE product_id = :product_id";
    $delete_product_stmt = $pdo->prepare($delete_product_query);
    $delete_product_stmt->bindParam(':product_id', $productId);
    $delete_product_stmt->execute();

    header("Location: ../seller/seller_index.php");
    exit();
} else {
    header("Location: ../seller/seller_index.php#view-products");
    exit();
}
