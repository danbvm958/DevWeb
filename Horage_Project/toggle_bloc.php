<?php
header('Content-Type: application/json');

// Chemin vers le fichier utilisateur
$jsonFile = 'data/utilisateur.json';

// Vérification des données reçues
if(!isset($_POST['email'])) {
    echo json_encode(['success' => false, 'message' => 'Aucun email fourni']);
    exit;
}
$email = $_POST['email'];

// Lecture des utilisateurs
$jsonData = file_get_contents($jsonFile);
$users = json_decode($jsonData, true);

$found = false;
$newEtat = 'non';

// Trouver l'utilisateur et changer le statut
foreach ($users as &$user) {
    if (isset($user['email']) && $user['email'] === $email) {
        // Toggle du champ "bloque" (tu peux ajuster selon ta logique : 1/0, true/false, "oui"/"non"…)
        if (!empty($user['bloque'])) {
            $user['bloque'] = false;
            $newEtat = 'non';
        } else {
            $user['bloque'] = true;
            $newEtat = 'oui';
        }
        $found = true;
        break;
    }
}
unset($user);

if ($found) {
    // Sauvegarde
    if (file_put_contents($jsonFile, json_encode($users, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'newEtat' => $newEtat]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur de sauvegarde JSON']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
}
?>
