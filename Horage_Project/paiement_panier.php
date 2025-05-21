<?php
require_once 'session.php';
$pdo = DemmarageSession();

if (VerificationConnexion() == 0) {
    header('Location: login.php');
    exit();
}

$voyage_id = $_GET['voyage_id'] ?? null;

header('Location: vers_CyBank.php');
exit();
?>