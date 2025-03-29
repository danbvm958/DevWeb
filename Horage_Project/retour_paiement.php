<?php
// retour_paiement.php
session_start();
require('getapikey.php');

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Récupérer les paramètres de l'URL
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$status = $_GET['status'] ?? '';
$control = $_GET['control'] ?? '';
$session_id = $_GET['session'] ?? '';

// Valider la session
if ($session_id !== session_id()) {
    die("Erreur: Session invalide");
}

// Valider la valeur de contrôle
$api_key = getAPIKey($vendeur);
$expected_control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($control === $expected_control) {
    // Paiement validé
    if ($status === 'accepted') {
        // Récupérer les données du voyage depuis la session
        if (isset($_SESSION['pending_payment'])) {

            $users_file = 'data/utilisateur.json';
            $users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : [];
            
            foreach ($users as &$user) {
                if ($user['email'] === $_SESSION['user']['email']) {
                    if (!isset($user['voyages'])) {
                        $user['voyages'] = [];
                    }
                    
                    $voyage_data = [
                        'voyage_id' => $_SESSION['pending_payment']['voyage_id'],
                        'voyage_titre' => $_SESSION['pending_payment']['voyage_titre'],
                        'nombre_personnes' => $_SESSION['pending_payment']['nombre_personnes'],
                        'options_choisies' => $_SESSION['pending_payment']['options_choisies'],
                        'etapes_supprimees' => $_SESSION['pending_payment']['etapes_supprimees'],
                        'date_achat' => date('Y-m-d H:i:s'),
                        'transaction_id' => $transaction,
                        'montant' => $montant
                    ];
                    
                    $user['voyages'][] = $voyage_data;
                    break;
                }
            }
            
            // Sauvegarder les modifications
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
            
            // Supprimer les données temporaires
            unset($_SESSION['pending_payment']);
            
            echo "<h1>Paiement réussi!</h1>";
            echo "<p>Transaction: $transaction</p>";
            echo "<p>Montant: $montant €</p>";
            echo "<p>Votre voyage a été enregistré dans votre compte.</p>";
        } else {
            echo "<h1>Paiement réussi mais erreur d'enregistrement</h1>";
            echo "<p>Contactez le support avec votre numéro de transaction: $transaction</p>";
        }
    } else {
        echo "<h1>Paiement refusé</h1>";
    }
} else {
    echo "<h1>Erreur: Données de paiement invalides</h1>";
}

echo '<a href="accueil.php">Retour à l\'accueil</a>';
?>