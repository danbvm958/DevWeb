// voyageData.js
// On passe les données du voyage au JavaScript
const voyageData = {
    prixBase: <?= $voyage['PrixBase'] ?>,
    reductions: <?= json_encode($reductions) ?>,
    placesDisponibles: <?= $voyage['PlacesDispo'] ?>
};

// Fonction pour formater les prix
function formatPrix(prix) {
    return prix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
