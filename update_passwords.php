<?php
require 'config/db.php';

// Извлечение всех пользователей
$stmt = $pdo->prepare('SELECT id, password FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

foreach ($users as $user) {
    // Хеширование пароля
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    // Обновление пароля в базе данных
    $update_stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $update_stmt->execute([$hashed_password, $user['id']]);
}

echo "Passwords updated successfully.";
?>
