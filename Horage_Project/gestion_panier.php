<?php
// On démarre la session pour accéder aux variables de session
DemarrageSession();

// On récupère les paiements en attente depuis la session
$pending_payment = $_SESSION['pending_payment']

// Fonction pour supprimer un voyage spécifique du panier
function supprimerVoyageDuPanier($voyageId) {
    // On initialise un index à 0 pour parcourir le tableau
    $index = 0;
    
    // On parcourt tous les voyages dans le panier
    foreach($pending_payment as $voyage){
        // On vérifie si l'ID du voyage correspond à celui recherché
        if($voyage['voyage_id'] == $voyageId){
            // Si on trouve le voyage, on sort de la boucle
            break;
        }
    // On incrémente l'index à chaque itération
    $index++;
    }
    
    // On supprime le voyage trouvé du tableau des paiements en attente
    unset($pending_payment[$index]);
}

// Fonction pour vider complètement le panier
function viderPanier($email) {
    // On supprime toutes les données des paiements en attente
    unset($pending_payment);
}
?>