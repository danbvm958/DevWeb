<?php

$pending_payment = $_SESSION['pending_payment']
function supprimerVoyageDuPanier($voyageId) {
    $index = 0;
    foreach($pending_payment as $voyage){
        if($voyage['voyage_id'] == $voyageId){
            break;
        }
    $index++;
    }
    unset($pending_payment[$index]);
}

function viderPanier($email) {
    unset($pending_payment);
}
?>