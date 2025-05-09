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

$dsn = 'mysql:host=localhost;dbname=ma_bdd;charset=utf8';
$user = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}            

if ($control === $expected_control) {
    // Paiement validé
    if ($status === 'accepted') {
        // Récupérer les données du voyage depuis la session
        if (isset($_SESSION['pending_payment'])) {
            $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE NomUtilisateur = ?");
            $stmt->execute([$_SESSION['user']['username']]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            $voyage_data = [
                'voyage_id' => $_SESSION['pending_payment'][$_SESSION['npayment']]['voyage_id'],
                'nombre_personnes' => $_SESSION['pending_payment'][$_SESSION['npayment']]['nombre_personnes'],
                'options_choisies' => $_SESSION['pending_payment'][$_SESSION['npayment']]['options_choisies'],
                'date_achat' => date('Y-m-d H:i:s'),
                'transaction_id' => $transaction,
                'montant' => $montant
            ];
            $stmt = $pdo->prepare("INSERT INTO voyage_payee (IdVoyage, IdUtilisateur, DatePaiement, Prix, NbAdultes, NbEnfants) VALUES (?,?,?,?,?,?) ");
            $stmt->execute([$voyage_data['voyage_id'],$utilisateur['Id'],$voyage_data['date_achat'],$voyage_data['montant'], $_SESSION['pending_payment'][$_SESSION['npayment']]['nombre_adultes'],$_SESSION['pending_payment'][$_SESSION['npayment']]['nombre_enfants']]);
            $idCommande = $pdo->lastInsertId();

            $stmt= $pdo->prepare("INSERT INTO options_commande (IdCommande,IdEtape,IdOption,IdChoix,Prix) VALUES (?,?,?,?,?) ");
            foreach($voyage_data['options_choisies'] as $id_etape => $options ){
                foreach($options as $option){
                    $stmt->execute([$idCommande,$id_etape,$option['id_option'],$option['id_choix'],$montant]);
                }
            }
            $stmt = $pdo->prepare("UPDATE voyage SET PlacesDispo = PlacesDispo - ? WHERE IdVoyage = ?");
            $stmt->execute([$voyage_data['nombre_personnes'],$voyage_data['voyage_id']]);

            
            unset($_SESSION['pending_payment'][$_SESSION['npayment']]);
            
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