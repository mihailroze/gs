<?php
require '../config/db.php';
session_start();

if (isset($_GET['category_id']) && isset($_SESSION['user_id'])) {
    $category_id = $_GET['category_id'];
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT * FROM services WHERE category_id = ? AND user_id != ?');
    $stmt->execute([$category_id, $user_id]);
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($services) {
        echo "<h2>Offers for category ID: " . htmlspecialchars($category_id) . ":</h2>";
        echo "<ul>";
        foreach ($services as $service) {
            echo "<li><a href='service.php?id=" . htmlspecialchars($service['id']) . "'>" . htmlspecialchars($service['service_name']) . " - " . htmlspecialchars($service['description']) . " - $" . htmlspecialchars($service['price']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<h2>No offers found for this category.</h2>";
    }
}
?>
