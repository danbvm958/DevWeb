<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['username'])) {
    echo "Utilisateur non authentifié.";
    exit();
}

$username = $_SESSION['user']['username']; // Identifiant unique de l'utilisateur

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

    // Charger le fichier JSON
    $file = 'data/utilisateur.json';
    if (!file_exists($file)) {
        echo "Fichier utilisateur introuvable.";
        exit();
    }

    $users = json_decode(file_get_contents($file), true);
    if ($users === null) {
        echo "Erreur de lecture du fichier JSON.";
        exit();
    }

    // Rechercher l'utilisateur et modifier la valeur
    $updated = false;
    foreach ($users as &$user) {
        if ($user['username'] === $username) {
            $user[$field] = $value;
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        echo "Utilisateur non trouvé.";
        exit();
    }

    // Sauvegarder les modifications dans le fichier JSON
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

    // Mettre à jour la session
    $_SESSION['user'][$field] = $value;

    echo "Mise à jour réussie.";
} else {
    echo "Données invalides.";
}
?>
