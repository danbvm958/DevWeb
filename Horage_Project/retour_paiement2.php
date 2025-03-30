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

// Vérifier l'intégrité de la session (sécurité)
if ($session_return !== session_id()) {
    die("Erreur : Session invalide.");
}

// Chargement des utilisateurs (JSON)
$file = __DIR__ . '/data/utilisateur.json';

if (!file_exists($file)) {
    die("Fichier utilisateur introuvable.");
}

$users = json_decode(file_get_contents($file), true);

if ($users === null) {
    die("Erreur de lecture des données utilisateurs.");
}

// Mise à jour du type d'utilisateur en VIP
$user_id = $_SESSION['vip_payment']['user_id'];
$user_updated = false;

foreach ($users as &$user) {
    if ($user['id'] === $user_id) {
        $user['type'] = 'VIP';
        $user_updated = true;

        // Mise à jour de la session actuelle aussi
        $_SESSION['user']['type'] = 'VIP';
        break;
    }
}

if (!$user_updated) {
    die("Utilisateur non trouvé pour la mise à jour.");
}

// Sauvegarder les modifications
file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

// Nettoyer la session du paiement VIP
unset($_SESSION['vip_payment']);

// Redirection vers le profil avec confirmation
header("Location: profil_user.php?status=vip_success");
exit();
?>
