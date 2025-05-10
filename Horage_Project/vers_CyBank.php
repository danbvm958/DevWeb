<?php
session_start();
require('getapikey.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fonction pour générer un ID de transaction valide (10-24 caractères alphanumériques)
function generateTransactionID() {
    $length = rand(10, 24);
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $result;
}

// Vérification méthode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Accès interdit.");
}

// Récupération des données du panier
$voyage_id = $_POST['voyage_id'] ?? null;
$nombre_personnes = $_POST['nombre_personnes'] ?? null;
$prix_total = $_POST['prix_total'] ?? null;

// Validation des données reçues
if (!is_numeric($prix_total)) {
    die("Erreur: Le montant doit être numérique");
}

if (!isset($_SESSION['pending_payment'])) {
    die("Erreur : Pas de voyage en attente de paiement.");
}

$index = $_POST['voyage_index'];
if(isset($index)){
    $_SESSION['npayment'] = $_POST['voyage_index'];
}
$transaction = generateTransactionID();
$montant = number_format((float)$_SESSION['pending_payment'][$_SESSION['npayment']]['prix_total'], 2, '.', '');
$vendeur = "MI-1_A";
$retour = "http://localhost/horage_project/retour_paiement.php?session=" . session_id();
// Récupération API Key
$api_key = getAPIKey($vendeur);

// Vérifications finales
if (!preg_match("/^[0-9a-zA-Z]{10,24}$/", $transaction)) {
    die("Erreur transaction: Format de l'identifiant invalide");
}

if ($api_key === "zzzz") {
    die("Erreur API Key: Code vendeur invalide");
}

if (!is_numeric($montant)) {
    die("Erreur montant: Le montant doit être numérique");
}

// Construction de la valeur de contrôle
$control_string = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#";
$control = md5($control_string);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de paiement</title>
    <script src="js/themeSwitcher.js" defer></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .payment-box { border: 1px solid #ddd; padding: 20px; border-radius: 5px; }
        .btn-payer { 
            background-color: #4CAF50; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="payment-box">
        <h2>Confirmez votre paiement</h2>
        <p>Montant : <strong><?= htmlspecialchars($montant) ?> €</strong></p>
        <p>Vous serez redirigé vers le service sécurisé CY Bank</p>
        <?php unset($_SESSION['npayment']); ?>
        
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?= htmlspecialchars($transaction) ?>">
            <input type="hidden" name="montant" value="<?= htmlspecialchars($montant) ?>">
            <input type="hidden" name="vendeur" value="<?= htmlspecialchars($vendeur) ?>">
            <input type="hidden" name="retour" value="<?= htmlspecialchars($retour) ?>">
            <input type="hidden" name="control" value="<?= htmlspecialchars($control) ?>">
            
            <button type="submit" class="btn-payer">
                ➔ Payer maintenant via CY Bank
            </button>
        </form>
        
        <p><small>Ce paiement est 100% sécurisé par CY Bank</small></p>
    </div>
</body>
</html>