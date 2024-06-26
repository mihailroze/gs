<?php
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $game_name = $_POST['game_name'];
    $contact = $_POST['contact'];

    // Логика для отправки предложения администратору (например, через email)
    // Например, используя mail() функцию PHP для отправки email
    $to = 'mihail.roze@yandex.ru'; // Замените на реальный email администратора
    $subject = 'New Game Suggestion';
    $message = "Game: $game_name\nContact: $contact";
    $headers = 'From: no-reply@example.com';

    mail($to, $subject, $message, $headers);

    echo "<p>Thank you for your suggestion! The admin has been notified.</p>";
}
?>
