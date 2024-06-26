<?php
require '../config/db.php';

if (isset($_GET['game'])) {
    $game_name = $_GET['game'];
    $stmt = $pdo->prepare('SELECT * FROM games WHERE name = ?');
    $stmt->execute([$game_name]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($game) {
        $stmt = $pdo->prepare('SELECT id, name FROM categories WHERE game_id = ?');
        $stmt->execute([$game['id']]);
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2>Services for " . htmlspecialchars($game_name) . ":</h2>";
        echo "<ul>";
        foreach ($categories as $category) {
            echo "<li class='category' data-id='" . htmlspecialchars($category['id']) . "'>" . htmlspecialchars($category['name']) . "</li>";
        }
        echo "</ul>";
        echo "<div id='offers'></div>";
    } else {
        echo "<h2>No game found.</h2>";
        echo "<p>You can suggest this game to the admin. Please provide your contact details.</p>";
        echo "<form method='post' action='../logic/suggest_game.php'>";
        echo "<input type='hidden' name='game_name' value='" . htmlspecialchars($game_name) . "'>";
        echo "<label for='contact'>Your Contact Info:</label>";
        echo "<input type='text' name='contact' required>";
        echo "<button type='submit'>Suggest Game</button>";
        echo "</form>";
    }
}
?>
