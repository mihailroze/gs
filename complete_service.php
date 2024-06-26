<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_id = $_POST['service_id'];
    $chat_session_id = $_POST['chat_session_id'];
    $user_id = $_SESSION['user_id'];

    // Получение информации о сессии чата
    $stmt = $pdo->prepare('SELECT * FROM chat_sessions WHERE id = ?');
    $stmt->execute([$chat_session_id]);
    $chat_session = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chat_session) {
        echo "Chat session not found.";
        exit();
    }

    // Получение информации об услуге
    $stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
    $stmt->execute([$service_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    // Проверка, что текущий пользователь является покупателем или исполнителем
    if ($user_id == $chat_session['buyer_id'] || $user_id == $service['user_id']) {
        if ($user_id == $chat_session['buyer_id']) {
            // Покупатель подтверждает завершение
            $stmt = $pdo->prepare('UPDATE chat_sessions SET buyer_confirmed = 1 WHERE id = ?');
            $stmt->execute([$chat_session_id]);
        } elseif ($user_id == $service['user_id']) {
            // Продавец завершает сделку после подтверждения покупателем
            if ($chat_session['buyer_confirmed']) {
                $stmt = $pdo->prepare('UPDATE chat_sessions SET is_completed = 1 WHERE id = ?');
                $stmt->execute([$chat_session_id]);

                // Удаление чата и сообщений после завершения сделки
                $stmt = $pdo->prepare('DELETE FROM messages WHERE chat_session_id = ?');
                $stmt->execute([$chat_session_id]);

                $stmt = $pdo->prepare('DELETE FROM chat_sessions WHERE id = ?');
                $stmt->execute([$chat_session_id]);
            } else {
                echo "Buyer has not confirmed the completion yet.";
                exit();
            }
        }
    } else {
        echo "You do not have permission to complete this service.";
        exit();
    }

    header("Location: ../public/profile.php");
}
?>
