<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = $_POST['game_id'];
    $game_name = $_POST['game_name'];
    $categories = $_POST['categories'];

    // Проверка, что пользователь администратор
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        header("Location: ../public/main.php");
        exit();
    }

    // Обновление игры в базе данных
    $stmt = $pdo->prepare('UPDATE games SET name = ? WHERE id = ?');
    $stmt->execute([$game_name, $game_id]);

    // Удаление старых категорий
    $stmt = $pdo->prepare('DELETE FROM categories WHERE game_id = ?');
    $stmt->execute([$game_id]);

    // Добавление новых категорий
    $stmt = $pdo->prepare('INSERT INTO categories (name, game_id) VALUES (?, ?)');
    foreach ($categories as $category_name) {
        if (!empty($category_name)) {
            $stmt->execute([$category_name, $game_id]);
        }
    }

    header("Location: ../public/add_game.php");
    exit();
}
?>
