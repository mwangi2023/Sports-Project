<?php
header('Content-Type: application/json');
require 'vendor/autoload.php'; // Load JWT
require 'db_connect.php';      // If you need to query the database

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = 'your-secret-key'; // Same secret key as login.php and verify_token.php

// Get headers
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Authorization header missing"]);
    exit;
}

// Extract token from header
$authHeader = $headers['Authorization'];
list($type, $token) = explode(" ", $authHeader, 2);

if (strcasecmp($type, 'Bearer') != 0 || empty($token)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid Authorization header format"]);
    exit;
}

try {
    // Decode JWT
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userData = $decoded->data;

    // Optionally verify user still exists in the database
    $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $userData->id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    $user = $result->fetch_assoc();

    // ✅ Protected content
    echo json_encode([
        "status" => "success",
        "message" => "Welcome to your dashboard!",
        "user" => $user
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or expired token",
        "error" => $e->getMessage()
    ]);
}
?>