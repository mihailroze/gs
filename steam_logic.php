<?php
require '../config/db.php';
require '../vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

session_start();

$oidc = new OpenIDConnectClient(
    'https://steamcommunity.com/openid',
    'CLIENT_ID', // Ваш клиентский ID
    'CLIENT_SECRET' // Ваш секретный ключ
);

$oidc->setRedirectURL('http://localhost/gamers/logic/steam_callback.php');
$oidc->addScope('openid');

if (!$oidc->authenticate()) {
    echo 'User has canceled authentication!';
} else {
    $steamID64 = $oidc->getVerifiedClaims('sub');

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
