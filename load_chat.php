<?php
require '../config/db.php';
session_start();

if (isset($_GET['service_id']) && isset($_GET['chat_session_id'])) {
    $service_id = $_GET['service_id'];
    $chat_session_id = $_GET['chat_session_id'];

    $stmt = $pdo->prepare('SELECT * FROM messages WHERE service_id = ? AND chat_session_id = ? ORDER BY created_at ASC');
    $stmt->execute([$service_id, $chat_session_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        echo "<div><strong>User " . htmlspecialchars($message['user_id']) . ":</strong> " . htmlspecialchars($message['message']) . "</div>";
    }
}
?>
