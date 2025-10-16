<?php
header('Content-Type: application/json');
require 'vendor/autoload.php'; // Load JWT library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = 'your-secret-key'; // Use same secret key as in login.php

// Get headers
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Authorization header missing"]);
    exit;
}

// Expected header format: Bearer <token>
$authHeader = $headers['Authorization'];
list($type, $token) = explode(" ", $authHeader, 2);

if (strcasecmp($type, 'Bearer') != 0 || empty($token)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid Authorization header format"]);
    exit;
}

try {
    // Decode the token
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

    // Token is valid → return success and user data
    echo json_encode([
        "status" => "success",
        "message" => "Token is valid",
        "user" => $decoded->data
    ]);

} catch (Exception $e) {
    // Token invalid or expired
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or expired token",
        "error" => $e->getMessage()
    ]);
}
?>