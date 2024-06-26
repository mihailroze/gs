<?php
include 'partials/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Проверка, что пользователь администратор
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: main.php");
    exit();
}

// Получение данных игры
$game_id = $_GET['game_id'];
$stmt = $pdo->prepare('SELECT * FROM games WHERE id = ?');
$stmt->execute([$game_id]);
$game = $stmt->fetch();

// Получение категорий игры
$stmt = $pdo->prepare('SELECT * FROM categories WHERE game_id = ?');
$stmt->execute([$game_id]);
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Game</title>
    <script>
        function addCategory() {
            const categoryList = document.getElementById('categoryList');
            const categoryInput = document.createElement('input');
            categoryInput.type = 'text';
            categoryInput.name = 'categories[]';
            categoryInput.placeholder = 'Category Name';
            categoryList.appendChild(categoryInput);
        }
    </script>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Edit Game</h1>
    <form method="post" action="../logic/edit_game_logic.php">
        <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
        <label for="game_name">Game Name:</label>
        <input type="text" name="game_name" id="game_name" value="<?= htmlspecialchars($game['name']) ?>" required>
        <br>
        <div id="categoryList">
            <label for="category_name">Categories:</label>
            <?php foreach ($categories as $category): ?>
                <input type="text" name="categories[]" value="<?= htmlspecialchars($category) ?>" placeholder="Category Name">
            <?php endforeach; ?>
        </div>
        <br>
        <button type="button" onclick="addCategory()">Add Another Category</button>
        <br><br>
        <button type="submit">Update Game and Categories</button>
    </form>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
