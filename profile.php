<?php
include 'partials/header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получение информации пользователя
require '../config/db.php';
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получение всех игр из базы данных
$stmt = $pdo->query('SELECT * FROM games');
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение завершенных сделок покупателя
$stmt = $pdo->prepare('
    SELECT s.id, s.service_name, c.is_completed
    FROM services s
    JOIN chat_sessions c ON s.id = c.service_id
    WHERE c.buyer_id = ? AND c.is_completed = 1
');
$stmt->execute([$_SESSION['user_id']]);
$completed_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение выполненных сделок продавца
$stmt = $pdo->prepare('
    SELECT s.id, s.service_name, c.is_completed
    FROM services s
    JOIN chat_sessions c ON s.id = c.service_id
    WHERE s.user_id = ? AND c.is_completed = 1
');
$stmt->execute([$_SESSION['user_id']]);
$fulfilled_services = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/profile.js"></script>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Profile Settings</h1>

    <form method="post" action="../logic/update_profile.php">
        <label for="telegram_chat_id">Telegram Chat ID:</label>
        <input type="text" id="telegram_chat_id" name="telegram_chat_id" value="<?= htmlspecialchars($user['telegram_chat_id']) ?>">
        <p>You can find your chat ID by messaging this bot: <a href="https://t.me/chatIDrobot" target="_blank">@chatIDrobot</a></p>
        <button type="submit">Update Profile</button>
    </form>

    <h2>Propose a Service</h2>
    <button id="proposeServiceButton">Propose Service</button>

    <div id="proposeServiceForm" style="display:none;">
        <form method="post" action="../logic/propose_service.php">
            <label for="game">Select Game:</label>
            <select id="game" name="game_id">
                <option value="">Select a game</option>
                <?php foreach ($games as $game): ?>
                    <option value="<?= $game['id'] ?>"><?= htmlspecialchars($game['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="category">Select Category:</label>
            <select id="category" name="category_id" disabled>
                <option value="">Select a category</option>
            </select>
            <br>
            <label for="serviceName">Service Name:</label>
            <input type="text" id="serviceName" name="service_name" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            <br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
            <br>
            <button type="submit">Add Service</button>
        </form>
    </div>

    <h2>Completed Services (Buyer)</h2>
    <ul>
        <?php foreach ($completed_services as $service): ?>
            <li><?= htmlspecialchars($service['service_name']) ?> (Service ID: <?= $service['id'] ?>)</li>
        <?php endforeach; ?>
    </ul>
    <h2>Fulfilled Services (Seller)</h2>
    <ul>
        <?php foreach ($fulfilled_services as $service): ?>
            <li><?= htmlspecialchars($service['service_name']) ?> (Service ID: <?= $service['id'] ?>)</li>
        <?php endforeach; ?>
    </ul>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
