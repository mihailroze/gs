<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_name = $_POST['game_name'];
    $categories = $_POST['categories'];

    // Проверка, что пользователь администратор
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        header("Location: ../public/main.php");
        exit();
    }

    // Добавление игры в базу данных
    $stmt = $pdo->prepare('INSERT INTO games (name) VALUES (?)');
    $stmt->execute([$game_name]);
    $game_id = $pdo->lastInsertId();

    // Добавление категорий в базу данных
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
