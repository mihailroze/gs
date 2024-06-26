<?php
include 'partials/header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: main.php");
    exit();
}

$service_id = $_GET['id'];

// Получение информации об услуге
$stmt = $pdo->prepare('SELECT * FROM services WHERE id = ?');
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "Service not found.";
    exit();
}

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Проверка, что текущий пользователь не является исполнителем
$is_owner = ($service['user_id'] == $user_id);

// Создание или получение существующего чата
$stmt = $pdo->prepare('SELECT * FROM chat_sessions WHERE service_id = ?');
$stmt->execute([$service_id]);
$chat_session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chat_session) {
    $stmt = $pdo->prepare('INSERT INTO chat_sessions (service_id, buyer_id) VALUES (?, ?)');
    $stmt->execute([$service_id, $user_id]);
    $chat_session_id = $pdo->lastInsertId();

    // Логика для отправки уведомления в Telegram
    $stmt = $pdo->prepare('SELECT telegram_chat_id FROM users WHERE id = ?');
    $stmt->execute([$service['user_id']]);
    $executor = $stmt->fetch(PDO::FETCH_ASSOC);

    $telegram_token = '6805120916:AAHGrEPn4k8UHi89MoQesCp-HwAZK0sEF8o';
    $telegram_chat_id = $executor['telegram_chat_id'];
    $chat_url = "http://yourdomain.com/public/service.php?id=$service_id";

    if (!empty($telegram_chat_id)) {
        $message = "New chat started for service: " . $service['service_name'] . ". Access the chat here: " . $chat_url;
        file_get_contents("https://api.telegram.org/bot$telegram_token/sendMessage?chat_id=$telegram_chat_id&text=" . urlencode($message));
    }
} else {
    $chat_session_id = $chat_session['id'];
}

// Проверка, что пользователь является покупателем или исполнителем
if (!$is_owner && $user_id != $chat_session['buyer_id']) {
    echo "You do not have permission to access this chat.";
    exit();
}

// Логика для добавления покупателя в список участников чата
if (!$is_owner && $chat_session['buyer_id'] != $user_id) {
    $stmt = $pdo->prepare('UPDATE chat_sessions SET buyer_id = ? WHERE id = ?');
    $stmt->execute([$user_id, $chat_session_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Chat</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/chat.js"></script>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Service Chat for <?= htmlspecialchars($service['service_name']) ?></h1>
    <?php if ($chat_session['is_completed']): ?>
        <p>This chat session has been completed.</p>
    <?php else: ?>
    <div id="chat" data-service-id="<?= htmlspecialchars($service_id) ?>" data-chat-session-id="<?= htmlspecialchars($chat_session_id) ?>">
        <!-- Здесь будет чат -->
    </div>
    <form id="chatForm">
        <input type="text" id="message" placeholder="Type your message...">
        <button type="submit">Send</button>
    </form>
    <?php if ($chat_session['buyer_confirmed'] && !$chat_session['is_completed']): ?>
        <p>Buyer has confirmed the completion. Waiting for seller's confirmation.</p>
    <?php endif; ?>
    <form id="completeServiceForm" method="post" action="../logic/complete_service.php">
        <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">
        <input type="hidden" name="chat_session_id" value="<?= htmlspecialchars($chat_session_id) ?>">
        <?php if ($user_id == $chat_session['buyer_id'] && !$chat_session['buyer_confirmed']): ?>
            <button type="submit">Confirm Completion</button>
        <?php elseif ($user_id == $service['user_id'] && $chat_session['buyer_confirmed'] && !$chat_session['is_completed']): ?>
            <button type="submit">Complete Service</button>
        <?php endif; ?>
    </form>
<?php endif; ?>
<?php include 'partials/footer.php'; ?>
</body>
</html>
