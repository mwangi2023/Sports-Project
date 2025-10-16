<?php
include 'db_connect.php';
session_start();

$user_id = $_SESSION['user_id'];
$sport = $_POST['sport'];
$duration = $_POST['duration'];
$date = $_POST['date'];
$notes = $_POST['notes'];

$sql = "INSERT INTO workouts (user_id, sport, duration, date, notes) 
        VALUES ('$user_id', '$sport', '$duration', '$date', '$notes')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>