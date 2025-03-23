<?php
session_start();
session_destroy(); // Détruit toutes les sessions
header("Location: accueil.php"); // Redirige vers l'accueil
exit();
?>