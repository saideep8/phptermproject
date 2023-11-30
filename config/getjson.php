<?php
require_once "pdo.php";
header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['email'])) {
    $email = getFormValue(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
    $check_email_query = "SELECT COUNT(*) FROM users WHERE email = :email";
    $check_email_stmt = $pdo->prepare($check_email_query);
    $check_email_stmt->bindParam(':email', $email);
    $check_email_stmt->execute();
    $email_exists = $check_email_stmt->fetchColumn();
    // Return JSON response
    echo json_encode(array('email_exists' => ($email_exists > 0)));
}
