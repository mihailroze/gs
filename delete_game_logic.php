<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_id = $_POST['game_id'];

    // Проверка, что пользователь администратор
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        header("Location: ../public/main.php");
        exit();
    }

    // Удаление категорий, связанных с игрой
    $stmt = $pdo->prepare('DELETE FROM categories WHERE game_id = ?');
    $stmt->execute([$game_id]);

    // Удаление игры из базы данных
    $stmt = $pdo->prepare('DELETE FROM games WHERE id = ?');
    $stmt->execute([$game_id]);

    header("Location: ../public/add_game.php");
    exit();
}
?>
