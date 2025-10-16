<?php
$host = "localhost";
$user = "root";     // XAMPP default
$pass = "#Nguono_22";         // leave empty unless you added a password
$db   = "mydb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

header("Access-Control-Allow-Origin: *");  // ✅ allows frontend requests
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
?>