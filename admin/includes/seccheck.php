<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != true || !isset($_SESSION['loggedin'])) {
    header("location: ../404.php", 404);
}
?>