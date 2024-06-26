<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        echo 'User found: ' . htmlspecialchars($user['username']) . '<br>';
    } else {
        echo 'User not found.<br>';
    }

    if ($user && password_verify($password, $user['password'])) {
        echo 'Password verified.<br>';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        header("Location: ../public/main.php");
        exit();
    } else {
        echo 'Invalid login credentials.<br>';
        $error = "Invalid login credentials.";
    }
}
?>
