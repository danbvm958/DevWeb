<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$utilisateurs = json_decode(file_get_contents('data/utilisateur.json'), true);
$utilisateur = null;

foreach ($utilisateurs as $u) {
    if ($u['email'] === $_SESSION['user']['email']) {
        $utilisateur = $u;
        break;
    }
}

$voyage_id = $_GET['voyage_id'] ?? null;

$voyage_a_payer = null;
foreach ($utilisateur['panier'] as $voyage) {
    if ($voyage['voyage_id'] === $voyage_id) {
        $voyage_a_payer = $voyage;
        break;
    }
}

if (!$voyage_a_payer) {
    die("Voyage non trouvé dans le panier");
}

$_SESSION['pending_payment'] = [
    'voyage_id' => $voyage_a_payer['voyage_id'],
    'voyage_titre' => $voyage_a_payer['voyage_titre'],
    'nombre_personnes' => $voyage_a_payer['nombre_personnes'],
    'nb_adultes' => $voyage_a_payer['nb_adultes'],
    'nb_enfants' => $voyage_a_payer['nb_enfants'],
    'options_choisies' => $voyage_a_payer['options_choisies'],
    'etapes_supprimees' => $voyage_a_payer['etapes_supprimees'],
    'prix_total' => $voyage_a_payer['prix_total']
];

header('Location: vers_CyBank.php');
exit();
?>