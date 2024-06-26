<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="main.php">Main</a></li>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <li><a href="add_game.php">Add Game</a></li>
        <?php endif; ?>
        <li><a href="../logic/logout.php">Logout</a></li>
    </ul>
</nav>
