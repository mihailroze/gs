<?php
require '../config/db.php';

if (isset($_GET['game_id'])) {
    $game_id = $_GET['game_id'];
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE game_id = ?');
    $stmt->execute([$game_id]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categories as $category) {
        echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
    }
}
?>
