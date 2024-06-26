<?php
require '../config/db.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $stmt = $pdo->prepare('SELECT name FROM games WHERE name LIKE ? LIMIT 5');
    $stmt->execute(["%$query%"]);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($games as $game) {
        echo "<div class='suggestion'>" . htmlspecialchars($game['name']) . "</div>";
    }
}
?>
