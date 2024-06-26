<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = $_POST['game_id'];
    $category_id = $_POST['category_id'];
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO services (game_id, category_id, user_id, service_name, description, price) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$game_id, $category_id, $user_id, $service_name, $description, $price]);

    header("Location: ../public/profile.php");
    exit();
}
?>
