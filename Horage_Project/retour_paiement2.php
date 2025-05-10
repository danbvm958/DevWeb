<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'], $_SESSION['vip_payment'])) {
    header("Location: login.php");
    exit();
}

// Récupération des paramètres de CY Bank
$transaction = $_GET['transaction'] ?? '';
$status = $_GET['status'] ?? '';
$session_return = $_GET['session'] ?? '';
$control = $_GET['control'] ?? '';

// Vérifier l'intégrité de la session (sécurité)
if ($session_return !== session_id()) {
    die("Erreur : Session invalide.");
}

$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}            


$user_id = $_SESSION['vip_payment']['user_id'];
$stmt = $pdo->prepare("UPDATE utilisateur SET Types = 'vip' WHERE Id = ?");
$stmt->execute([$user_id]);
$_SESSION['user']['type']= "vip";

unset($_SESSION['vip_payment']);

header("Location: profil_user.php");
exit();
?>
