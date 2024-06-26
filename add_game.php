<?php
include '../partials/header.php';
session_start();

// Проверка, что пользователь администратор
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: main.php");
    exit();
}

// Получение всех игр и категорий из базы данных
require '../config/db.php';
$stmt = $pdo->query('SELECT * FROM games');
$games = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Games</title>
    <script>
        function addCategory(containerId) {
            const container = document.getElementById(containerId);
            const categoryInput = document.createElement('input');
            categoryInput.type = 'text';
            categoryInput.name = 'categories[]';
            categoryInput.placeholder = 'Category Name';
            container.appendChild(categoryInput);
        }
    </script>
</head>
<body>
    <?php include '../partials/navbar.php'; ?>
    <h1>Manage Games</h1>
    <h2>Add New Game</h2>
    <form method="post" action="../logic/add_game_logic.php">
        <label for="game_name">Game Name:</label>
        <input type="text" name="game_name" id="game_name" required>
        <br>
        <div id="newCategoryList">
            <label for="category_name">Categories:</label>
            <input type="text" name="categories[]" placeholder="Category Name">
        </div>
        <br>
        <button type="button" onclick="addCategory('newCategoryList')">Add Another Category</button>
        <br><br>
        <button type="submit">Add Game and Categories</button>
    </form>

    <h2>Existing Games</h2>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <form method="post" action="../logic/delete_game_logic.php" style="display:inline;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
                <form method="post" action="../logic/edit_game_logic.php" style="display:inline;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <input type="text" name="game_name" value="<?= htmlspecialchars($game['name']) ?>">
                    <div id="categoryList<?= $game['id'] ?>">
                        <?php
                        $stmt = $pdo->prepare('SELECT * FROM categories WHERE game_id = ?');
                        $stmt->execute([$game['id']]);
                        $categories = $stmt->fetchAll();
                        foreach ($categories as $category):
                        ?>
                            <input type="text" name="categories[]" value="<?= htmlspecialchars($category['name']) ?>">
                            <form method="post" action="../logic/delete_category_logic.php" style="display:inline;">
                                <input type="hidden" name="category_id" value="<?= $category['id'] ?>">
                                <button type="submit">Delete Category</button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" onclick="addCategory('categoryList<?= $game['id'] ?>')">Add Another Category</button>
                    <button type="submit">Update</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php include '../partials/footer.php'; ?>
</body>
</html>
