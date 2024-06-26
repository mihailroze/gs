<?php
require '../config/db.php';
require '../vendor/SteamAuth/steamauth/steamauth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['steamid'])) {
    steamlogin(); // Начать процесс авторизации
} else {
    $steamID64 = $_SESSION['steamid'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE steam_id = ?');
    $stmt->execute([$steamID64]);
    $user = $stmt->fetch();

    if (!$user) {
        $stmt = $pdo->prepare('INSERT INTO users (steam_id, username) VALUES (?, ?)');
        $stmt->execute([$steamID64, 'SteamUser' . $steamID64]);
        $user_id = $pdo->lastInsertId();
    } else {
        $user_id = $user['id'];
    }

    $_SESSION['user_id'] = $user_id;
    $_SESSION['is_admin'] = $user['is_admin'];
    header('Location: ../public/main.php');
    exit();
}
?>
