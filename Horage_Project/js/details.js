document.addEventListener('DOMContentLoaded', function() {
    const inputAdultes = document.getElementById('nb_adultes');
    const inputEnfants = document.getElementById('nb_enfants');
    const affichagePrix = document.querySelector('.prix');
    const optionsCheckboxes = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');
    
    inputAdultes.addEventListener('input', calculerPrixTotal);
    inputEnfants.addEventListener('input', calculerPrixTotal);
    optionsCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculerPrixTotal);
    });
    
    function calculerPrixTotal() {
        const nbAdultes = parseInt(inputAdultes.value) || 0;
        const nbEnfants = parseInt(inputEnfants.value) || 0;
        const totalPersonnes = nbAdultes + nbEnfants;
        
        if (nbAdultes < 1 || nbEnfants < 0 || totalPersonnes > voyageData.placesDisponibles) {
            affichagePrix.textContent = "0 €";
            return;
        }
        
        let prixBase = voyageData.prixBase;
        let prixTotal = 0;
        

        voyageData.reductions.forEach(reduction => {
            if (reduction.type_reduction === 'groupe' && 
                totalPersonnes >= reduction.condition.min_personnes) {
                prixBase = reduction.prix_reduit;
            }
        });
        

        prixTotal += nbAdultes * prixBase;
        

        let reductionEnfantAppliquee = false;
        voyageData.reductions.forEach(reduction => {
            if (!reductionEnfantAppliquee && 
                reduction.type_reduction === 'enfant' && 
                nbEnfants >= reduction.condition.min_enfants) {
                prixTotal += nbEnfants * (prixBase - reduction.remise_par_enfant);
                reductionEnfantAppliquee = true;
            }
        });
        
        if (!reductionEnfantAppliquee) {
            prixTotal += nbEnfants * prixBase;
        }
        
 
        let prixOptions = 0;
        document.querySelectorAll('input[type="checkbox"]:checked, input[type="radio"]:checked').forEach(option => {
            const parts = option.value.split('|');
            if (parts.length === 2) {
                prixOptions += parseFloat(parts[1]);
            }
        });
        
        prixTotal += prixOptions;
        
        affichagePrix.textContent = formatPrix(Math.round(prixTotal)) + ' €';
    }
    

    calculerPrixTotal();
});