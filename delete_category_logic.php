<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];

    // Проверка, что пользователь администратор
    if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
        header("Location: ../public/main.php");
        exit();
    }

    // Удаление категории из базы данных
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->execute([$category_id]);

    header("Location: ../public/add_game.php");
    exit();
}
?>
