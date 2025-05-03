<?php
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
                    // Ajouter le voyage aux voyages achetés
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
                    
                    // Supprimer le voyage du panier (version corrigée)
                    if (isset($user['panier'])) {
                        $voyage_id_to_remove = $_SESSION['pending_payment']['voyage_id'];
                        $user['panier'] = array_filter($user['panier'], function($item) use ($voyage_id_to_remove) {
                            return $item['voyage_id'] !== $voyage_id_to_remove;
                        });
                    }
                    
                    break;
                }
            }
            
            // Sauvegarder les modifications
            file_put_contents($users_file, json_encode($users, JSON_PRETTY_PRINT));
            
            // Supprimer les données temporaires
            unset($_SESSION['pending_payment']);
            
            // Message de succès
            $_SESSION['payment_message'] = [
                'type' => 'success',
                'title' => 'Paiement réussi!',
                'content' => "Transaction: $transaction<br>Montant: $montant €<br>Votre voyage a été enregistré dans votre compte."
            ];
        } else {
            $_SESSION['payment_message'] = [
                'type' => 'warning',
                'title' => 'Paiement réussi mais erreur d\'enregistrement',
                'content' => "Contactez le support avec votre numéro de transaction: $transaction"
            ];
        }
    } else {
        $_SESSION['payment_message'] = [
            'type' => 'error',
            'title' => 'Paiement refusé',
            'content' => 'Votre paiement a été refusé par le système de paiement.'
        ];
    }
} else {
    $_SESSION['payment_message'] = [
        'type' => 'error',
        'title' => 'Erreur: Données de paiement invalides',
        'content' => 'Les données de paiement n\'ont pas pu être vérifiées.'
    ];
}

header("Location: profil_travel.php");
exit();
?>