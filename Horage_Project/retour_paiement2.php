<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();
if(VerificationConnexion == 0){
    header(Location : "accueil.php");
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


$user_id = $_SESSION['vip_payment']['user_id'];
$stmt = $pdo->prepare("UPDATE utilisateur SET Types = 'vip' WHERE Id = ?");
$stmt->execute([$user_id]);
$_SESSION['user']['type']= "vip";

unset($_SESSION['vip_payment']);

header("Location: profil_user.php");
exit();
?>
