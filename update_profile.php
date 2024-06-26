<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telegram_chat_id = $_POST['telegram_chat_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('UPDATE users SET telegram_chat_id = ? WHERE id = ?');
    $stmt->execute([$telegram_chat_id, $user_id]);

    header("Location: ../public/profile.php");
    exit();
}
?>
