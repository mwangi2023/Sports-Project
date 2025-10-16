<?php
header('Content-Type: application/json');
require 'db_connect.php';
require 'vendor/login.php'; // Load JWT library

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = 'your-secret-key'; // Use a strong, private key

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $payload = [
            'iss' => 'http://localhost', // issuer
            'aud' => 'http://localhost', // audience
            'iat' => time(),             // issued at
            'exp' => time() + 3600,      // expires in 1 hour
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "token" => $jwt
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Incorrect password"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>