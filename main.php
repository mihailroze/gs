<?php
include 'partials/header.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/main.js"></script>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    <h1>Welcome to the Main Page</h1>

    <input type="text" id="search" placeholder="Search for a game...">
    <button id="searchButton">Search</button>
    <div id="suggestions"></div>

    <div id="services"></div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
