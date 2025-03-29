<?php
// retour_paiement.php
session_start();
require('getapikey.php');

// Récupérer les paramètres de l'URL
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$status = $_GET['status'] ?? '';
$control = $_GET['control'] ?? '';

// Valider la valeur de contrôle
$api_key = getAPIKey($vendeur);
$expected_control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($control === $expected_control) {
    // Paiement validé
    if ($status === 'accepted') {
        echo "<h1>Paiement réussi!</h1>";
        echo "<p>Transaction: $transaction</p>";
        echo "<p>Montant: $montant €</p>";
        // Ici vous pourriez enregistrer en base de données
    } else {
        echo "<h1>Paiement refusé</h1>";
    }
} else {
    echo "<h1>Erreur: Données de paiement invalides</h1>";
}

echo '<a href="accueil.php">Retour à l\'accueil</a>';
?>