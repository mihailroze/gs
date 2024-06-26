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

// Получение всех игр и категорий из базы данных
$stmt = $pdo->query('SELECT * FROM games');
$games = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Games</title>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Manage Games</h1>
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <strong><?= htmlspecialchars($game['name']) ?></strong>
                <form method="post" action="../logic/delete_game_logic.php" style="display:inline;">
                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
                <a href="edit_game.php?game_id=<?= $game['id'] ?>">Edit</a>
                <ul>
                    <?php
                    $stmt = $pdo->prepare('SELECT * FROM categories WHERE game_id = ?');
                    $stmt->execute([$game['id']]);
                    $categories = $stmt->fetchAll();
                    foreach ($categories as $category):
                    ?>
                        <li><?= htmlspecialchars($category['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="add_game.php">Add New Game</a>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
