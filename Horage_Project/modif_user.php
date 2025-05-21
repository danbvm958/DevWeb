<?php
require_once 'session.php';
DemarrageSession();
$pdo = DemarrageSQL();
if (VerificationConnexion() == 0){
    header(Location : "accueil.php");
}

// Vérifier si les données sont envoyées
if (isset($_POST['field'], $_POST['value'])) {
    $field = $_POST['field'];
    $value = trim($_POST['value']);

    // Sécurité : Autoriser uniquement certains champs à être modifiés
    $allowed_fields = ['username', 'nom', 'prenom', 'email'];
    if (!in_array($field, $allowed_fields)) {
        echo "Champ non autorisé.";
        exit();
    }
    switch($field){
        case 'username':
            $nfield = 'NomUtilisateur';
            break;
        case 'nom':
            $nfield = 'Nom';
            break;
        case 'prenom':
            $nfield = 'Prenom';
            break;
        case 'email':
            $nfield = 'Email';
            break;
    }

    $stmt = $pdo->prepare("UPDATE utilisateur SET $nfield = ? WHERE Id = ?");
    $stmt->execute([$value,$_SESSION['user']['id']]);

    // Mettre à jour la session
    $_SESSION['user'][$field] = $value;

    echo "Mise à jour réussie.";
} else {
    echo "Données invalides.";
}
?>
