<?php
// get_user.php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

echo json_encode([
    "id" => $_SESSION['user_id'],
    "name" => $_SESSION['name'],
    "email" => $_SESSION['email']
]);
?>