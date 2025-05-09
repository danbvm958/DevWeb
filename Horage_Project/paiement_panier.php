<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$voyage_id = $_GET['voyage_id'] ?? null;

header('Location: vers_CyBank.php');
exit();
?>