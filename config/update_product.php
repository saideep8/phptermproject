<?php
include('../config/pdo.php');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productId = getFormValue(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $newPrice =  getFormValue(INPUT_POST,  'new_price', FILTER_DEFAULT);
    $newQuantity = getFormValue(INPUT_POST,  'new_quantity', FILTER_VALIDATE_INT);

    $updateQuery = "UPDATE products SET price = :new_price, quantity = :new_quantity WHERE product_id = :product_id";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':new_price', $newPrice);
    $updateStmt->bindParam(':new_quantity', $newQuantity);
    $updateStmt->bindParam(':product_id', $productId);

    try {
        $updateStmt->execute();
        echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error updating data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
