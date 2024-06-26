<?php include 'partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Login</h1>
    <?php if (!empty($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="../logic/login_logic.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
    <br>
    <?php
    require '../vendor/SteamAuth/steamauth/steamauth.php';
    loginbutton("square"); // Используем функцию loginbutton
    ?>
    <?php include 'partials/footer.php'; ?>
</body>
</html>
