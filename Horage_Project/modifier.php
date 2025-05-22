<?php
require_once 'session.php';
DemarrageSession();

// On supprime le dernier élément de la session pending_payment
if (isset($_SESSION['pending_payment']) && is_array($_SESSION['pending_payment']) && !empty($_SESSION['pending_payment'])) {
    array_pop($_SESSION['pending_payment']);
}

// On redirige vers la page de modification
$voyage_id = $_GET['id'] ?? null;
$nb_adultes = $_GET['nb_adultes'] ?? 0;
$nb_enfants = $_GET['nb_enfants'] ?? 0;

if ($voyage_id !== null) {
    header("Location: voyages_details.php?id=$voyage_id&nb_adultes=$nb_adultes&nb_enfants=$nb_enfants");
    exit;
} else {
    echo "Paramètres manquants.";
}
?>