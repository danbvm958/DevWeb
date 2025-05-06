<?php

function supprimerVoyageDuPanier($email, $voyageId) {
    $filePath = 'data/utilisateur.json';
    
    $utilisateurs = json_decode(file_get_contents($filePath), true);
    
    foreach ($utilisateurs as &$utilisateur) {
        if ($utilisateur['email'] === $email) {
            foreach ($utilisateur['panier'] as $key => $voyage) {
                if ($voyage['voyage_id'] == $voyageId) {
                    unset($utilisateur['panier'][$key]);
                    $utilisateur['panier'] = array_values($utilisateur['panier']);
                    file_put_contents($filePath, json_encode($utilisateurs, JSON_PRETTY_PRINT));
                    return true;
                }
            }
        }
    }
    return false;
}

function viderPanier($email) {
    $filePath = 'data/utilisateur.json';
    
    $utilisateurs = json_decode(file_get_contents($filePath), true);
    
    foreach ($utilisateurs as &$utilisateur) {
        if ($utilisateur['email'] === $email) {
            $utilisateur['panier'] = [];
            file_put_contents($filePath, json_encode($utilisateurs, JSON_PRETTY_PRINT));
            return true;
        }
    }
    return false;
}
?>