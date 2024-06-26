<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../config/db.php';
?>
