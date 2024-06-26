<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $chat_session_id = $_POST['chat_session_id'];
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO messages (service_id, chat_session_id, user_id, message, created_at) VALUES (?, ?, ?, ?, NOW())');
    $stmt->execute([$service_id, $chat_session_id, $user_id, $message]);
}
?>
